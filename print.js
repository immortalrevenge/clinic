

function printInvoice() {
    // Get the content of the specific div
    var printContent = document.getElementById("print-content").innerHTML;

    // Create a new window
    var printWindow = window.open("", "", "width=800,height=600");

    // Write the div content into the new window
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Invoice</title>
            <link rel="stylesheet" href="userStyle.css" />
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);

    // Close the document to finish writing
    printWindow.document.close();

    // Give it a slight delay to ensure the document is ready
    setTimeout(function() {
        // Trigger the print dialog
        printWindow.print();

        // Close the print window after printing
        printWindow.close();
    }, 5000);
}