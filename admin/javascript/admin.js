import { response_codes } from './enums.js';
import { foundEmpty } from './functions.js';

const rootPath = "http://localhost/Catering-MCO/queries/admin/";

$(document).ready(function () {
    // LOGIN PAGE START
    $('#login-submit').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: rootPath.concat("loginProcess.php"),
            data: $(this).serialize(),
            success: function (response) {
                const data = JSON.parse(response);
            },
            error: function (status, error) {
                console.error('AJAX error: ' + status + ': ' + error);
                alert('An error occurred while processing your request. Please try again later.');
            }
        });
    });
    // LOGIN PAGE END

    // ADMIN PACKAGE PAGE START
    $('#add_package_submit').on('submit', function (e) { 
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: rootPath.concat("addPackage.php"),
            data: $(this).serialize(),
            success: function (response) {
                const data = JSON.parse(response);
                switch (data.status) {
                    case response_codes.insert_success:
                        document.getElementById('add_package_submit').reset();
                        $('#alertMsg').text(data.msg);
                        break;
                    case response_codes.failed_process:
                        $('#alertMsg').text(data.msg);
                        break;
                    case response_codes.invalid_request:
                        $('#alertMsg').text(data.msg);
                        break;
                    default:
                        break;
                }
            },
            error: function (status, error) {
                console.error('AJAX error: ' + status + ': ' + error);
                alert('An error occurred while processing your request. Please try again later.');
            }
        });
    });
    // ADMIN PACKAGE PAGE END
    
    // ADD ACCOUNT PAGE START
    $('#add-admin-form').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        // check if password are match
        if (foundEmpty(formData)) {
            return $('#alertMsg').text('Empty fields are not allowed').css('color', 'crimson');
        }
        // see if fields are empty
        if (formData.get('password').trim() !== formData.get('confirm-password').trim()) {
            return $('#alertMsg').text('Password did not match').css('color', 'crimson');
        }
        $.ajax({
            type: "POST",
            url: rootPath.concat("addAccount.php"),
            data: formData,
            success: function (response) {
                
            },
            error: function (status, error) {
                console.error('AJAX error: ' + status + ': ' + error);
                alert('An error occurred while processing your request. Please try again later.');
            }
        });
       
    });
    // ADD ACCOUNT PAGE END
});
