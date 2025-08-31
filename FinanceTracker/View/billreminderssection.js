
document.addEventListener("DOMContentLoaded", () => {
  const billForm = document.getElementById("billForm");
  const billTableBody = document.querySelector("#billTable tbody");

  billForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const billName = document.getElementById("billName").value.trim();
    const dueDate = document.getElementById("billDueDate").value;
    const autoPay = document.getElementById("autoPay").checked;

    if (billName && dueDate) {
      const due = new Date(dueDate);
      const today = new Date();
      const diffDays = Math.ceil((due - today) / (1000 * 60 * 60 * 24));
      let alertText = "";

      if (diffDays > 0 && diffDays <= 3) {
        alertText = "⚠️ Due Soon!";
      } else if (diffDays < 0) {
        alertText = "❌ Overdue!";
      } else {
        alertText = "✅ On Time";
      }

      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>${billName}</td>
        <td>${dueDate}</td>
        <td>${autoPay ? "Yes" : "No"}</td>
        <td>${alertText}</td>
        <td><button class="btn btn-danger btn-sm delete-bill-btn">Delete</button></td>
      `;

      billTableBody.appendChild(newRow);

      // Clear form
      document.getElementById("billName").value = "";
      document.getElementById("billDueDate").value = "";
      document.getElementById("autoPay").checked = false;
    }
  });

  // Delete functionality
  billTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete-bill-btn")) {
      const row = e.target.closest("tr");
      row.remove();
    }
  });
});
