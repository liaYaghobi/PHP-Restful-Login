const login = document.querySelector('.buttonlogin')
login.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('.loginform'))
    var lstatus
    fetch('api.php', {
            'method': 'POST',
          
            'body': formData,
            mode: 'cors',
            credentials: 'include'
        })
        .then(res => {
            lstatus = res.status
            console.log('user cookies is ')
            console.log(res.headers.get('user'))
            
            return res.text()
        })
        .then(data => {
            console.log(document.cookie)
            alert(data)
            if (lstatus == 200)
            location.href = "frontpage.html"
        })
        .catch(err => {

            console.log(err)
        })

})

const register = document.querySelector('.buttonregister')
register.addEventListener('click', () => {
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
