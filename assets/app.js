/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// jquery
const $ = require('jquery');
require('bootstrap');

// start the Stimulus application
import './bootstrap';


$('.postulatedButton').click(function () {
    let jobId = $(this).attr("jobId");
    let authenticatedUser = $(this).attr("user");
    let validatedUser = $(this).attr("validatedUser");
    if (authenticatedUser == 1) {
        if (validatedUser == 0) {
            alert('Votre compte dot avoir été validé avant de pouvoir postuler!');
        }else {
            alert('Votre demande a été prise en compte, elle est en cours de validation.');
            return [$(location).attr("href", '/jobs/postulate/'+jobId)];
        }
    } else {
        return alert('vous devez être connecté pour pouvoir postuler!');
    }
})






