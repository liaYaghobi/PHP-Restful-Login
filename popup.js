const submitReview = document.querySelector('.buttonreview');
const formReview = document.querySelector('#reviewform');

formReview.addEventListener('submit', (event) => {
    const queryParams = new URLSearchParams(window.location.search);
    const itemId = queryParams.get('itemId');
    const username = queryParams.get('username');
    event.preventDefault();
    const formData = new FormData(document.querySelector('.reviewform'))
    formData.append('item_id', itemId);//add itemID to formData
    formData.append('username', username);
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
        location.href = "popup.html"
    })
    .catch(err => {
        console.log(err)
    })
})