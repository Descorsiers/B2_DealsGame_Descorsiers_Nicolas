import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function(){

    const deleteAnnouncement = document.querySelectorAll('.deleteAnnouncement');
    const deletePopup = document.querySelector('.popup');
    const deleteForm = document.querySelector('.deleteForm');
    const inputHide = document.querySelector('.inputHide');

    deleteAnnouncement.forEach(element => {
        element.addEventListener('click', function(){
            inputHide.value = element.dataset.id;
            deleteForm.action = deleteForm.action.replace('__ID__', element.dataset.id);
            console.log(deleteForm);
        })
    });
    
})

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
