/**
 * Smart Hospital Management System - Main JS
 * Includes Search Filtering, Form Validation & Utility Functions
 */

document.addEventListener("DOMContentLoaded", function () {
  // Sidebar Toggle functionality
  const sidebarCollapse = document.getElementById("sidebarCollapse");
  const sidebar = document.getElementById("sidebar");

  if (sidebarCollapse && sidebar) {
    sidebarCollapse.addEventListener("click", function () {
      sidebar.classList.toggle("active");
    });
  }

  // Live Table Search Filter
  const searchInput = document.getElementById("tableSearchInput");
  const dataTable = document.getElementById("dataTable");

  if (searchInput && dataTable) {
    searchInput.addEventListener("keyup", function () {
      const filter = searchInput.value.toLowerCase().trim();
      const rows = dataTable.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

      for (let i = 0; i < rows.length; i++) {
        const text = rows[i].textContent.toLowerCase();
        if (text.indexOf(filter) > -1) {
          rows[i].style.display = "";
        } else {
          rows[i].style.display = "none";
        }
      }
    });
  }

  // Auto-dismiss alerts after 5 seconds
  const alertList = document.querySelectorAll(".alert-dismissible");
  alertList.forEach(function (alert) {
    setTimeout(function () {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
});

/**
 * Print Prescription or Invoice
 */
function printDocument(elementId) {
  const content = document.getElementById(elementId).innerHTML;
  const printWindow = window.open("", "", "height=700,width=900");
  printWindow.document.write("<html><head><title>Print Document</title>");
  printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
  printWindow.document.write('<style>body{padding:20px; font-family:sans-serif;} @media print{.no-print{display:none;}}</style>');
  printWindow.document.write("</head><body>");
  printWindow.document.write(content);
  printWindow.document.write("</body></html>");
  printWindow.document.close();
  printWindow.focus();
  setTimeout(function () {
    printWindow.print();
    printWindow.close();
  }, 500);
}
