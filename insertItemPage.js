
let categoryList = document.getElementById("categoryList");
let tagsInput = document.getElementById("tags-input");
let addTagButton = document.getElementById("add-tag-button");

const submitItem = document.querySelector('.buttonSubmitItem')
submitItem.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('.itemform'))
    formData.append('categories', prepareCategories(categoryList.children));
    var status
    fetch('api.php', {
            'method': 'POST',
            'body': formData,
            mode: 'cors',
            credentials: 'include'
        })
        .then(response => {
            status = response.status;
            return response.text();
        })
        .then(data => {
            alert(data)
            if (status == 200)
            location.href = "insertItemPage.html"
        })
        .catch(err => {
            console.log(err)
        })
})


addTagButton.addEventListener("click", function() {

  let tag = tagsInput.value.trim();

  tagsInput.value = "";
  
  if (tag !== "") {

    let li = document.createElement("li");
    li.textContent = tag;
    categoryList.appendChild(li);
  }

});

function prepareCategories(list) {
    let concatenatedString = "";
    
    for (let i = 0; i < list.length; i++) {
      concatenatedString += list[i].textContent.trim();
      
      if (i !== list.length - 1) {
        concatenatedString += ",";
      }
    }
    
    return concatenatedString;
  }