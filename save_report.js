    function save() {
        Swal.fire({
            title: 'Print Report',
            text: 'Are you sure to save this report',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Update password logic here
                $.post('./tcpdf/inventory.php', {
                    startDate: ". addslashes()"
                }, function(response) {
                    console.log(response); // Debugging: Check the server response in browser console
                    Swal.fire('Updated!', 'Password updated successfully!', 'success').then(() => {
                        window.location.replace('userAcctInfo');
                    });
                }).fail(function(xhr, status, error) {
                    console.log(xhr.responseText); // Debugging: Output any errors
                    Swal.fire('Error', 'Failed to update password!', 'error');
                });
            }
        });
    }