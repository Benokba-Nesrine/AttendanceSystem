// =====================
// Core Table & Calculations
// =====================
const table = document.getElementById("attendanceTable");
const tbody = table.tBodies[0];

// Update a single row (absences, participation, message, color)
function updateRow(row) {
  const presence = row.querySelectorAll(".presence input");
  const participation = row.querySelectorAll(".participation input");

  let presentCount = 0;
  presence.forEach(cb => { if(cb.checked) presentCount++; });

  let partCount = 0;
  participation.forEach(cb => { if(cb.checked) partCount++; });

  const absences = presence.length - presentCount;
  row.querySelector(".abs").textContent = absences;
  row.querySelector(".parNbr").textContent = partCount;

  // Set row color
  row.classList.remove("highlight-green", "highlight-yellow", "highlight-red");
  if(absences < 3) row.classList.add("highlight-green");
  else if(absences <= 4) row.classList.add("highlight-yellow");
  else row.classList.add("highlight-red");

  // Set message
  const msgCell = row.querySelector(".msg");
  let message = "";
  if(absences >= 5)
    message = "Excluded — too many absences — You need to participate more";
  else if(absences >= 3)
    message = partCount >= 3 ? "Warning — attendance low — Participation OK"
                             : "Warning — attendance low — You need to participate more";
  else
    message = partCount >= 3 ? "Good attendance — Excellent participation"
                             : "Good attendance — You should participate more";

  msgCell.textContent = message;
}

// Update entire table
function updateTable() {
  Array.from(tbody.rows).forEach(updateRow);
}

// =====================
// Load Students from Server
// =====================
function loadStudents() {
  fetch('list_students.php')
    .then(res => res.text())
    .then(html => {
      tbody.innerHTML = html;

      // Update table calculations
      updateTable();

      // Attach events
      attachRowEvents();
      attachCheckboxListeners();
    })
    .catch(err => {
      console.error("Failed to load students:", err);
      tbody.innerHTML = '<tr><td colspan="18">Failed to load students.</td></tr>';
    });
}

// =====================
// Row Hover & Click Events
// =====================
function attachRowEvents() {
  Array.from(tbody.rows).forEach(row => {
    row.onmouseenter = () => row.classList.add("row-hover");
    row.onmouseleave = () => row.classList.remove("row-hover");
    row.onclick = () => {
      const last = row.cells[1].textContent;
      const first = row.cells[2].textContent;
      const absences = row.querySelector(".abs").textContent;
      alert(`Student: ${first} ${last}\nAbsences: ${absences}`);
    };
  });
}

// =====================
// Checkbox Event Listeners (AJAX Update)
// =====================
function attachCheckboxListeners() {
  Array.from(tbody.rows).forEach(row => {
    const studentId = row.cells[0].textContent;

    row.querySelectorAll('input[type="checkbox"]').forEach((checkbox, idx) => {
      checkbox.onchange = function() {
        const session = Math.floor(idx / 2) + 1; // 0-1 → S1, 2-3 → S2...
        const type = (idx % 2 === 0) ? 'p' : 'pa';
        const value = this.checked ? 1 : 0;

        fetch('update_table.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `student_id=${encodeURIComponent(studentId)}&session=${session}&type=${type}&value=${value}`
        })
        .then(res => res.json())
        .then(data => {
          if (!data.success) console.error("Update failed:", data.message);
          updateRow(row); // recalc row colors/messages
        })
        .catch(err => console.error("Error updating database:", err));
      };
    });
  });
}

// =====================
// Highlight Excellent / Reset Buttons
// =====================
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("highlightExcellentBtn").onclick = () => {
    Array.from(tbody.rows).forEach(row => {
      row.classList.remove("glow-effect");
      if(parseInt(row.querySelector(".abs").textContent) < 3) {
        row.classList.add("glow-effect");
      }
    });
  };

  document.getElementById("resetColorsBtn").onclick = () => {
    Array.from(tbody.rows).forEach(row => {
      row.classList.remove("glow-effect");
      updateRow(row);
    });
  };

  // Initial load
  loadStudents();

  // Optional: continuously update table calculations every 1 second
  setInterval(updateTable, 1000);
});
