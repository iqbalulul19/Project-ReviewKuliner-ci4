<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-container {
            max-width: 600px;
            margin: 60px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .icon-circle {
            width: 120px;
            height: 120px;
            background-color: #00a693; /* Warna Teal khas gambar */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px auto;
        }
        .icon-circle svg {
            color: white;
            width: 60px;
            height: 60px;
        }
        .title-text {
            font-size: 2.5rem;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-align: center;
        }
        .subtitle-text {
            font-size: 1.1rem;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .details-box {
            border: 1px solid #eaeaea;
            border-radius: 12px;
            padding: 30px;
            background-color: #fafafa;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: #111;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            width: 40%;
            font-weight: 500;
        }
        .detail-value {
            width: 60%;
        }
        .btn-back {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 30px;
            padding: 12px;
            background-color: #f1f1f1;
            color: #333;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }
        .btn-back:hover {
            background-color: #e2e2e2;
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-container">
        
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <h1 class="title-text">Payment Success</h1>
        <p class="subtitle-text">Here is your transaction details.</p>

        <div class="mb-3 d-flex justify-content-between">
            <span class="text-muted fw-semibold">Customer Name:</span>
            <span class="fw-bold text-dark"><?= esc($customer_name); ?></span>
        </div>

        <div class="mb-3 d-flex justify-content-between">
            <span class="text-muted fw-semibold">Invoice Number:</span>
            <span class="fw-bold text-dark"><?= esc($order_id); ?></span>
        </div>

        <div class="mb-4 d-flex justify-content-between">
            <span class="text-muted fw-semibold">Transaction Amount:</span>
            <span class="fw-bold text-success">IDR <?= number_format($amount, 0, ',', '.'); ?></span>
        </div>
        <div class="mb-4 d-flex justify-content-between">
            <span class="text-muted fw-semibold">Transaction Status:</span>
            <span class="fw-bold text-primary"><?= esc(ucfirst($status)); ?></span>
        </div>
        <a href="<?= base_url('/') ?>" class="btn-back">Back to Home</a>

        </div>
    </div>

</body>
</html>