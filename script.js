  function updateDateTime() {
    const now = new Date();
    const options = {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit"
    };
    document.getElementById("dateTime").textContent =
      now.toLocaleString("en-US", options);
  }

  setInterval(updateDateTime, 1000);
  updateDateTime();
  function analyseTable(){
    let table= getElementById("attendanceTable");
    let abs;
    let par;
    for(let i=2;i<table.rows.length;i++){
      let row = table.rows[i];
      abs=0;
      par=0;
      for(let j=3;j<15;j++){
        let cell = row.cells[j];
         let box = cell.querySelector("input");
        if(j%2==0 && box ==true){
          par++;
        }
        if(j%2!=0 && box==false){
          abs++;
        }
      }
      
      row.cells[15].innerHtml=abs;
      row.cells[15].innerHtml=par;
    }
  }
  setInterval(analyzeTable, 10000);
