<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Corrected path to autoload.php
    require_once __DIR__ . '/../vendor/autoload.php'; // Correct path to autoload
    require_once __DIR__ . '/../includes/db.php';     // Assuming db.php is inside includes/
    

   
    
    

    $mpdf = new \Mpdf\Mpdf(); // Ensure the Mpdf class is loaded
    header('Content-Type: application/pdf');

    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = 1;

    $html = '
    <html>
    <head>
        <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1, h4 { text-align: center; color: #333; } /* added h4 */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f0f0f0; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
    </head>
    <body>
        <h4>List of Products</h4>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($products as $product) {
        $html .= '
                <tr>
                    <td>' . $count++ . '</td>
                    <td>' . htmlspecialchars($product['name']) . '</td>
                    <td>' . htmlspecialchars($product['category']) . '</td>
                    <td>' . htmlspecialchars($product['price']) . '</td>
                    <td>' . htmlspecialchars($product['stock']) . '</td>
                </tr>';
    }

    $html .= '
            </tbody>
        </table>
        <div class="signature-section">
            <div class="signature">
                <p>________________________________________________________</p>
                <p><strong> General Manager</strong></p>
            </div> 
        </div>
    </body>
    </html>';

    $mpdf->SetHTMLFooter('
        <div style="text-align: left; font-size: 10px; color: #aaa;">
            Page {PAGENO}/{nbpg}
        </div>');

    $mpdf->WriteHTML($html);
    $mpdf->Output('', 'I');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Products</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>

    <form method="POST" action="simple_print.php" class="container mt-5">
        <button type="submit" class="btn btn-primary">
            Print Products
        </button>
    </form>

</body>
</html>