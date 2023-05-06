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

        const messageIndex = data.findIndex(row => row.message === "No users found");
        if (messageIndex >= 0) {
          // Display the message in a table row
          const messageRow = table.insertRow();
          const messageCell = messageRow.insertCell();
          messageCell.textContent = data[messageIndex].message;
          messageCell.colSpan = 1;
        } else {
      
        for (const row of data) {
          const tableRow = table.insertRow();
          tableRow.insertCell().textContent = row.username;
        }
  
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

  let queryEight = document.getElementById("num_eight");
  queryEight.addEventListener('click', () => {
    fetch(`api.php?crud_req=phase3num8`, {
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
  
      // Check if any results found
      const messageIndex = data.findIndex(row => row.message === "No users found");
      if (messageIndex >= 0) {
        // Display the message in a table row
        const messageRow = table.insertRow();
        const messageCell = messageRow.insertCell();
        messageCell.textContent = data[messageIndex].message;
        messageCell.colSpan = 1;
      } else {
        // Display the results in table rows
        for (const row of data) {
          const tableRow = table.insertRow();
          tableRow.insertCell().textContent = row.username;
        }
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
  
  let queryNine = document.getElementById("num_nine");
  queryNine.addEventListener('click', () => {
    fetch(`api.php?crud_req=phase3num9`, {
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
  
      // Check if any results found
      const messageIndex = data.findIndex(row => row.message === "No users found");
      if (messageIndex >= 0) {
        // Display the message in a table row
        const messageRow = table.insertRow();
        const messageCell = messageRow.insertCell();
        messageCell.textContent = data[messageIndex].message;
        messageCell.colSpan = 1;
      } else {
        // Display the results in table rows
        for (const row of data) {
          const tableRow = table.insertRow();
          tableRow.insertCell().textContent = row.username;
        }
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


  let queryTen = document.getElementById("num_ten");
  queryTen.addEventListener('click', () => {
    fetch(`api.php?crud_req=phase3num10`, {
      method: 'GET',
      mode: 'cors',
      credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
      const table = document.createElement('table');
      const headerRow = table.insertRow();
  
      headerRow.style.backgroundColor = '#ccc';
      headerRow.insertCell().textContent = 'User A';
      headerRow.insertCell().textContent = 'User B';
  
      // Check if any results found
      const messageIndex = data.findIndex(row => row.message === "No users found");
      if (messageIndex >= 0) {
        // Display the message in a table row
        const messageRow = table.insertRow();
        const messageCell = messageRow.insertCell();
        messageCell.textContent = data[messageIndex].message;
        messageCell.colSpan = 1;
      } else {
        // Display the results in table rows
        for (const row of data) {
          const tableRow = table.insertRow();
          tableRow.insertCell().textContent = row.user_posted;
          tableRow.insertCell().textContent = row.user_reviewed;
        }
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
  
  




