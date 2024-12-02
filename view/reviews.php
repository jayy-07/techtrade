<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechTrade</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../css/home.css" rel="stylesheet" />
</head>


<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5" id="review-container">

        <!-- Start of a Single Review Card -->
        <div class="row mb-4">
            <!-- Game Image -->
            <div class="col-md-2">
                <a href="product_page.php?product_id=101">
                    <img src="https://via.placeholder.com/200" class="img-fluid" style="object-fit: cover; width: 200px; height: 200px; border-radius: 5px;" alt="Product Image">
                </a>
            </div>

            <!-- Review Content -->
            <div class="col-md-8">
                <!-- Product Title -->
                <a href="product_page.php?product_id=101" style="text-decoration: none; color: inherit;">
                    <p style="font-size: 30px;"><strong>Apple iPhone 15 128GB (Unlocked)</strong></p>
                </a>

                <!-- Star Rating -->
                <div class="rating-group">
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star text-secondary"></span>
                </div>

                <!-- Review Text -->
                <div class="review">
                    <p style="margin-top: 10px;">Fantastic camera and battery life, but a bit pricey.</p>
                </div>

                <!-- Edit and Delete Buttons -->
                <br>
                <div class="buttons">
                    <button type="button" id="editButton" class="btn btn-primary" data-review-id="101" data-bs-toggle="modal" data-bs-target="#editReviewModal">Edit your review</button>
                    <button type="button" id="deleteButton" style="margin-left: 10px;" class="btn btn-danger" data-review-id="101" data-bs-toggle="modal" data-bs-target="#deleteReviewModal">Delete your review</button>
                </div>
            </div>
        </div>
        <hr class="mb-4">

        <!-- Repeat this block for additional reviews -->

        <div class="row mb-4">
            <div class="col-md-2">
                <a href="product_page.php?product_id=102">
                    <img src="https://via.placeholder.com/200" class="img-fluid" style="object-fit: cover; width: 200px; height: 200px; border-radius: 5px;" alt="Product Image">
                </a>
            </div>
            <div class="col-md-8">
                <a href="product_page.php?product_id=102" style="text-decoration: none; color: inherit;">
                    <p style="font-size: 30px;"><strong>Samsung Galaxy S23 Ultra</strong></p>
                </a>
                <div class="rating-group">
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                    <span class="fa fa-star checked text-warning"></span>
                </div>
                <div class="review">
                    <p style="margin-top: 10px;">Absolutely love the display and performance. Worth every penny!</p>
                </div>
                <br>
                <div class="buttons">
                    <button type="button" id="editButton" class="btn btn-primary" data-review-id="102" data-bs-toggle="modal" data-bs-target="#editReviewModal">Edit your review</button>
                    <button type="button" id="deleteButton" style="margin-left: 10px;" class="btn btn-danger" data-review-id="102" data-bs-toggle="modal" data-bs-target="#deleteReviewModal">Delete your review</button>
                </div>
            </div>
        </div>
        <hr class="mb-4">

    </div>



    <!-- Edit Review Modal -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Edit Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Star Rating -->
                    <div class="rating-group">
                        <input class="rating__input rating__input--none" name="rating" id="rating-none" value="0" type="radio">
                        <label aria-label="No rating" class="rating__label" for="rating-none"><i class="rating__icon rating__icon--none fa fa-ban"></i></label>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <label aria-label="<?= $i ?> star" class="rating__label" for="rating-<?= $i ?>"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                            <input class="rating__input" name="rating" id="rating-<?= $i ?>" value="<?= $i ?>" type="radio">
                        <?php endfor; ?>
                    </div>
                    <!-- Review Text -->
                    <textarea id="reviewText" class="form-control mt-3" rows="3" maxlength="4000" placeholder="What did you think about the product?"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Review Modal -->
    <div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteReviewModalLabel">Delete Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this review?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/jquery.min.js"></script>