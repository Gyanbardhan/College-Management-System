document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("open-login-button").addEventListener('click', function() {
        window.location.href = 'http://localhost/DBMS_PROJECT/site/Login_page.php';
    });
    openPDF();

});
function openPDF() {
    // Replace 'path/to/your/file.pdf' with the actual path to your PDF file
    var pdfPath = 'Academic_Calendar_23-24_Higher.pdf';

    // Open the PDF file in a new window or tab
    window.open(pdfPath, '_blank');
  }