<!-- resources/views/print_receipt.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Receipt</title>
</head>
<body>
    <!-- Receipt Container -->
    <div id="receipt-container">
        {!! $receipt !!}
    </div>

    <script>
        // Function to print the receipt content
        function printReceipt(content) {
            // Create a new window for the print
            var printWindow = window.open('', '_blank', 'width=600,height=400');
            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Automatically print the receipt content
        document.addEventListener('DOMContentLoaded', function() {
            var receiptContent = document.getElementById('receipt-container').innerHTML;
            printReceipt(receiptContent);
        });
    </script>
</body>
</html>
