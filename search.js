const search = document.querySelector('.submitsearch');
const searchForm = document.querySelector('#search-form');
const searchResults = document.querySelector('#search-results');

searchForm.addEventListener('submit', (event) => {
event.preventDefault();
  const formData = new FormData(document.querySelector('.search-form'));
  const params = new URLSearchParams(formData).toString();
  fetch(`api.php?${params}`, {//passes crud_req=search & category = test
    method: 'GET',
    mode: 'cors',
    credentials: 'include'
  })
  .then(response => response.json())
  .then(data => {
    const table = document.createElement('table');
    const headerRow = table.insertRow();
    headerRow.insertCell().textContent = 'Item ID';
    headerRow.insertCell().textContent = 'Item Name';
    headerRow.insertCell().textContent = 'Category';
    headerRow.insertCell().textContent = 'Description';
    headerRow.insertCell().textContent = 'Price';
    for (const row of data) {
        const tableRow = table.insertRow();
        tableRow.insertCell().textContent = row.item_id;
        tableRow.insertCell().textContent = row.item_name;
        tableRow.insertCell().textContent = row.categories;
        tableRow.insertCell().textContent = row.description;
        tableRow.insertCell().textContent = row.price;

        tableRow.addEventListener('click', () => {
            const width = 400;
            const height = 400;
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);
            //prevent resizing?
            const popup = window.open('popup.html', '', `width=${width},height=${height},left=${left},top=${top}`);
            popup.focus();
        })
    }
    const existingTable = document.querySelector('table');
    if (existingTable) {
      existingTable.parentNode.replaceChild(table, existingTable);
    } else {
      document.body.appendChild(table);
    }
  })
  .catch(error => console.error(error));
});
