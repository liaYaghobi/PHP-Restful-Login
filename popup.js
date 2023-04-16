const submitReview = document.querySelector('.buttonreview');
const formReview = document.querySelector('#reviewform');

formReview.addEventListener('submit', (event) => {
    event.preventDefault();
    const formData = new FormData(document.querySelector('.reviewform'))
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