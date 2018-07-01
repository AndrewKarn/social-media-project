const userMain = () => {
    const header = document.querySelector('header');
    header.onclick = navigate;
    document.onkeyup = navigate;

    function navigate(e) {
        if (e.key === "Enter" || e instanceof MouseEvent) {

            const focused = document.activeElement;

            if (focused.nodeName === 'LI' || focused.nodeName === 'I') {
                document.querySelector('.selected').classList.remove('selected');
                focused.classList.add('selected');
                const id = focused.getAttribute('id');
                switch (id) {
                    case 'js-user-home':

                        break;
                    case 'js-user-message':

                        break;
                    case 'js-user-profile':

                        break;
                    case 'js-user-settings':

                        break;
                    case 'js-main-home':
                }
            }
        }
    }
    switch (window.location.pathname) {
        case '/home':
        case '/user/home':
        case '/user/home/':
        case '/user/home#':
            document.getElementById('js-user-home').classList.add('selected');
            break;
        case '/user/messages':
        case '/user/messages/':
        case '/user/messages#':
            document.getElementById('js-user-message').classList.add('selected');
            break;
        case '/user/profile':
        case '/user/profile/':
        case '/user/profile#':
            document.getElementById('js-nav-login').classList.add('selected');
            break;
        case '/user/settings':
        case '/user/settings/':
        case '/user/settings#':
            document.getElementById('js-user-settings').classList.add('selected');
            break;
    }
    navBar.addEventListener('click', evt => {
        if (evt.target.nodeName === "A") {
            document.querySelector('.selected').classList.remove('selected');
            evt.target.classList.toggle('selected');
        }
    });


};
document.addEventListener('DOMContentLoaded', userMain);