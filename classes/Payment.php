<?php
require_once '../settings/db_class.php';
require_once '../settings/config.php';

class Payment extends db_connection {
    private $paystack_secret_key;
    
    public function __construct() {
        $this->db_connect();
        $this->paystack_secret_key = PAYSTACK_SECRET_KEY;
    }
    
    public function initializePayment($order_id, $email, $amount) {
        try {
            error_log("Payment Class - Starting payment initialization");
            error_log("Payment Class - Data: order_id=$order_id, email=$email, amount=$amount");

            $url = "https://api.paystack.co/transaction/initialize";
            $reference = 'TRX_'.time().'_'.$order_id;
            
            $fields = [
                'email' => $email,
                'amount' => round($amount * 100),
                'reference' => $reference,
                'callback_url' => BASE_URL . '/actions/verify_payment.php',
                'metadata' => [
                    'order_id' => $order_id
                ]
            ];

            error_log("Payment Class - Request payload: " . json_encode($fields));

            // Initialize database connection
            $this->db_connect();

            // Check if payment record already exists
            $checkSql = "SELECT payment_id FROM payments WHERE order_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bind_param("i", $order_id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows === 0) {
                // Only create a new payment record if one doesn't exist
                $this->storePaymentInit($order_id, $reference, $amount);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $this->paystack_secret_key,
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                error_log("Payment Class - Curl Error: " . curl_error($ch));
                throw new Exception("Payment gateway connection failed");
            }

            curl_close($ch);
            
            error_log("Payment Class - Paystack Response: " . $result);
            
            $transaction = json_decode($result, true);
            
            if (!$transaction) {
                error_log("Payment Class - JSON decode failed");
                throw new Exception("Invalid response from payment gateway");
            }

            if ($transaction['status']) {
                // Store payment initialization details
                $this->storePaymentInit($order_id, $transaction['data']['reference'], $amount);
                error_log("Payment Class - Payment initialized successfully");
                return $transaction['data'];
            } else {
                error_log("Payment Class - Payment initialization failed: " . ($transaction['message'] ?? 'Unknown error'));
                throw new Exception($transaction['message'] ?? 'Payment initialization failed');
            }

        } catch (Exception $e) {
            error_log("Payment Class - Error: " . $e->getMessage());
            return false;
        }
    }

    public function verifyPayment($reference) {
        try {
            $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $this->paystack_secret_key,
                "Cache-Control: no-cache",
            ]);
            
            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new Exception("Curl error: " . curl_error($ch));
            }
            
            curl_close($ch);
            
            $response = json_decode($result, true);
            
            if (!$response) {
                throw new Exception("Failed to decode Paystack response");
            }
            
            if ($response['status'] && $response['data']['status'] === 'success') {
                return [
                    'status' => 'success',
                    'reference' => $response['data']['reference'],
                    'amount' => $response['data']['amount'] / 100, // Convert from kobo to naira
                    'metadata' => $response['data']['metadata'] ?? []
                ];
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Payment verification error: " . $e->getMessage());
            return false;
        }
    }

    private function storePaymentInit($order_id, $reference, $amount) {
        try {
            $sql = "INSERT INTO payments (
                order_id,
                payment_reference,
                amount,
                payment_status,
                created_at
            ) VALUES (?, ?, ?, 'Pending', NOW())";
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->db->error);
            }
            
            $stmt->bind_param("isd", $order_id, $reference, $amount);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to store payment init: " . $stmt->error);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Payment Class - Error storing payment init: " . $e->getMessage());
            throw $e;
        }
    }

    private function updatePaymentStatus($reference, $status, $transaction_data) {
        try {
            $this->db->begin_transaction();

            // Update payment record
            $sql = "UPDATE payments SET 
                    payment_status = ?, 
                    transaction_data = ? 
                    WHERE payment_reference = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sss", $status, $transaction_data, $reference);
            $stmt->execute();

            // Update order status
            $sql = "UPDATE orders SET 
                    payment_status = ? 
                    WHERE payment_reference = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss", $status, $reference);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error updating payment status: " . $e->getMessage());
            return false;
        }
    }

    public function storeTransaction($data) {
        try {
            $this->db_connect();
            
            // First check if we already have a completed transaction
            $checkSql = "SELECT payment_id FROM payments 
                        WHERE order_id = ? AND payment_status = 'Completed'";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bind_param("i", $data['order_id']);
            $checkStmt->execute();
            
            if ($checkStmt->get_result()->num_rows > 0) {
                error_log("Payment already completed for order: " . $data['order_id']);
                return true; // Payment already processed
            }
            
            // Update existing pending payment record
            $sql = "UPDATE payments SET 
                    payment_status = ?,
                    transaction_data = ?,
                    updated_at = NOW()
                    WHERE order_id = ? 
                    AND payment_reference = ? 
                    AND payment_status = 'Pending'
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->db->error);
            }
            
            $stmt->bind_param(
                "ssis",
                $data['status'],
                $data['transaction_data'],
                $data['order_id'],
                $data['reference']
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update transaction: " . $stmt->error);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Payment Class - Error updating transaction: " . $e->getMessage());
            return false;
        }
    }
} 