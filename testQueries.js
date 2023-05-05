let resultOne = document.getElementById("query_results");

let queryOne = document.getElementById("num_one");
queryOne.addEventListener('click', () => {
  fetch(`api.php?crud_req=phase3num1`, {
      method: 'GET',
      mode: 'cors',
      credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
      const table = document.createElement('table');
      const headerRow = table.insertRow();

      headerRow.style.backgroundColor = '#ccc';
      headerRow.insertCell().textContent = 'Item Name';
      headerRow.insertCell().textContent = 'Category';
      headerRow.insertCell().textContent = 'Price';

      for (const row of data) {
        const tableRow = table.insertRow();
        tableRow.insertCell().textContent = row.item_name;
        tableRow.insertCell().textContent = row.category;
        tableRow.insertCell().textContent = row.price;
      }

      const existingTable = document.querySelector('table');
      if (existingTable) {
        existingTable.parentNode.replaceChild(table, existingTable);
      } else {
        resultOne.appendChild(table);
      }
    })
    .catch(error => console.error(error));
});

let querySeven = document.getElementById("num_seven");
querySeven.addEventListener('click', () => {
    fetch(`api.php?crud_req=phase3num7`, {
        method: 'GET',
        mode: 'cors',
        credentials: 'include'
      })
      .then(response => response.json())
      .then(data => {
        const table = document.createElement('table');
        const headerRow = table.insertRow();

        headerRow.style.backgroundColor = '#ccc';
        headerRow.insertCell().textContent = 'Users';
      
        for (const row of data) {
          const tableRow = table.insertRow();
          tableRow.insertCell().textContent = row.username;
        }
  
        const existingTable = document.querySelector('table');
        if (existingTable) {
          existingTable.parentNode.replaceChild(table, existingTable);
        } else {
          resultOne.appendChild(table);
        }
      })
      .catch(error => console.error(error));
  });


