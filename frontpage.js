const initialize = document.querySelector('.buttondatabase')
initialize.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('.dataform'))
    fetch('api.php?crud_req=initialize', {
        'method': 'POST',

        'body': formData,
        credentials: 'include',
        mode: 'cors'
    })
    .then(res => res.text())
    .then(data => {
        alert(data)
        location.href = 'frontpage.html'
    })
    .catch(error => {
        console.error('Error:', error);
      });
})

const login = document.querySelector('.buttonlogin')
login.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('.loginform'))

    var status
    fetch('api.php', {
            'method': 'POST',
          
            'body': formData,
            mode: 'cors',
            credentials: 'include'
        })
        .then(res => {
            status = res.status
            console.log('user cookies is ')
            console.log(res.headers.get('user'))
           
            return res.text()
        })
        .then(data => {
            console.log(document.cookie)
            alert(data)
            if (status == 200)
            location.href = "frontpage.html"
        })
        .catch(err => {

            console.log(err)
        })

})

const register = document.querySelector('.buttonregister')
register.addEventListener('click', () => {
    if (!validateInput())
        return false;
    const formData = new FormData(document.querySelector('.registerform'))
    var status
    fetch('api.php', {
            'method': 'POST',
          
            'body': formData,
            mode: 'cors',
            credentials: 'include'
        })
        .then(res => {
            status = res.status
            console.log('user cookies is ')
            console.log(res.headers.get('user'))
            
            return res.text()
        })
        .then(data => {
            console.log(document.cookie)
            alert(data)
            if (status == 200)
            location.href = "frontpage.html"
        })
        .catch(err => {

            console.log(err)
        })
})

function validateInput(){
    var pass1 = document.getElementById('pass').value
    var pass2 = document.getElementById('confirm_pass').value
    var user = document.getElementById('user').value
    var email = document.getElementById('email').value
    var firstName = document.getElementById('first').value
    var lastName = document.getElementById('last').value
    
    if (!isValidEmail(email)) {
        event.preventDefault();
        document.getElementById('email').value = '';
        alert('Incorrect email format. Please try again.');
        return false;
    }      
    //nonempty fields --> HTML required attribute
    //check if email has proper domain?
    if (pass1 !== pass2){
        document.getElementById('pass').value = '';
        document.getElementById('confirm_pass').value = '';
        alert('Passwords dont match, please try again.');
        return false;
    }
    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

const logout = document.querySelector(".logout")
logout.addEventListener('click', () => {

    fetch('api.php', {
      
          
            credentials: 'include',
            mode: 'cors'
        })
        .then(res => res.text())
        .then(data => {
            alert(data)
            location.href = 'frontpage.html'
        })
})