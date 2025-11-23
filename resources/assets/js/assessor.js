// error trapping
function validateForm(fields) {
  let valid = true;

  // Loop through all fields to check if any are empty
  fields.forEach(field => {
    const input = document.getElementById(field.id);
    const value = input.value.trim();
    const errorMessages = [];

    // Check for empty fields
    if (!value) {
      valid = false;
      errorMessages.push(`${field.label} is required.`);
    }

    if (field.id === 'password-confirmation' && value) {
      const password = document.getElementById('password').value.trim();
      if (value !== password) {
        valid = false;
        errorMessages.push('Passwords do not match.');
      }
    }

    if (errorMessages.length > 0) {
      input.classList.add('is-invalid'); // Add Bootstrap 'is-invalid' class
      let errorMessageContainer = input.parentNode.querySelector('.invalid-feedback');
      if (!errorMessageContainer) {
        errorMessageContainer = document.createElement('div');
        errorMessageContainer.classList.add('invalid-feedback');
        input.parentNode.appendChild(errorMessageContainer);
      }
      errorMessageContainer.innerHTML = errorMessages.join('<br>'); // Display all errors for this field
    } else {
      input.classList.remove('is-invalid'); // Remove 'is-invalid' class if valid
      let errorMessageContainer = input.parentNode.querySelector('.invalid-feedback');
      if (errorMessageContainer) {
        errorMessageContainer.remove(); // Remove error messages
      }
    }
  });

  return valid;
}

$(document).ready(function () {
  $('#AddAssessorBtn').on('click', function (event) {
    const fields = [
      { id: 'firstname', label: 'First Name' },
      { id: 'lastname', label: 'Last Name' },
      { id: 'phone', label: 'Phone Number' },
      { id: 'address', label: 'Address' },
      { id: 'role', label: 'Position' }
    ];
    const isValid = validateForm(fields);
    if (!isValid) {
      event.preventDefault();
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/assessor/add',
      cache: false,
      data: $('#PropertyData').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#AddAssessor').modal('hide');
        $('.preloader').show();
      },
      success: function (data) {
        $('.preloader').hide();
        if (data.Error == 1) {
          Swal.fire('Error!', data.Message, 'error');
        } else if (data.Error == 0) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Saved!',
            text: data.Message,
            showConfirmButton: true,
            confirmButtonText: 'OK'
          }).then(result => {
            location.reload();
          });
        }
      },
      error: function () {
        $('.preloader').hide();
        Swal.fire('Error!', 'Something went wrong, please try again.', 'error');
      }
    });
  });
});

// search
$(document).ready(function () {
  function displayProperties(assessors) {
    const $assessorlist = $('#assessorlist');
    $assessorlist.empty();

    if (assessors.length === 0) {
      $assessorlist.html(`<tr><td colspan="9" class="text-center text-muted">No assessors found.</td></tr>`);
      return;
    }

    assessors.forEach(assessor => {
      const assessorRow = `
        <tr>
          <td>${assessor.firstname} ${assessor.lastname}</td>
          <td>${assessor.address}</td>
          <td>${assessor.phone}</td>
          <td>${assessor.role}</td>
          <td><span class="badge bg-label-success">${assessor.status}</span></td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item Edit" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#EditAssessor"
                  data-id="${assessor.encrypted_id}"
                  data-firstname="${assessor.firstname}"
                  data-middlename="${assessor.middlename}"
                  data-lastname="${assessor.lastname}"
                  data-phone="${assessor.phone}"
                  data-address="${assessor.address}"
                  data-role="${assessor.role}"
                  >
                  <i class="ri-pencil-line me-1"></i> Edit
                </a>
                <a class="dropdown-item DeleteBtn" href="javascript:void(0);" data-id="${assessor.encrypted_id}">
                  <i class="ri-delete-bin-6-line me-1"></i> Delete
                </a>
              </div>
            </div>
          </td>
        </tr>
      `;
      $assessorlist.append(assessorRow);
    });
  }

  function filterProperties(query) {
    const filtered = window.assessors.filter(assessor => {
      return assessor.firstname.toLowerCase().includes(query) || assessor.address.toLowerCase().includes(query);
    });
    displayProperties(filtered);
  }

  $('#search').on('input', function () {
    const query = $(this).val().toLowerCase();
    filterProperties(query);
  });

  // Render all properties on page load
  displayProperties(window.assessors);
});

$(document).ready(function () {
  $('body').on('click', '.Edit', function () {
    const id = $(this).data('id');
    const firstname = $(this).data('firstname');
    const middlename = $(this).data('middlename');
    const lastname = $(this).data('lastname');
    const phone = $(this).data('phone');
    const address = $(this).data('address');
    const role = $(this).data('role');

    $('#Edit_encrypted_id').val(id);
    $('#Edit_firstname').val(firstname);
    $('#Edit_middlename').val(middlename);
    $('#Edit_lastname').val(lastname);
    $('#Edit_phone').val(phone);
    $('#Edit_address').val(address);
    $('#Edit_role').val(role);
  });

  // Update assessor
  $('#UpdateAssessorBtn').on('click', function (event) {
    const fields = [
      { id: 'Edit_firstname', label: 'First Name' },
      { id: 'Edit_lastname', label: 'Last Name' },
      { id: 'Edit_phone', label: 'Phone Number' },
      { id: 'Edit_address', label: 'Address' },
      { id: 'Edit_role', label: 'Position' }
    ];
    const isValid = validateForm(fields);
    if (!isValid) {
      event.preventDefault();
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/assessor/update',
      cache: false,
      data: $('#PropertyDataUpdate').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#EditAssessor').modal('hide');
        $('.preloader').show();
      },
      success: function (data) {
        $('.preloader').hide();
        if (data.Error == 1) {
          Swal.fire('Error!', data.Message, 'error');
        } else if (data.Error == 0) {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Saved!',
            text: data.Message,
            showConfirmButton: true,
            confirmButtonText: 'OK'
          }).then(result => {
            location.reload();
          });
        }
      },
      error: function () {
        $('.preloader').hide();
        Swal.fire('Error!', 'Something went wrong, please try again.', 'error');
      }
    });
  });
});

$(document).ready(function () {
  $('body').on('click', '.DeleteBtn', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: '/assessor/delete',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id
          },
          dataType: 'json',
          beforeSend: function () {
            $('.preloader').show();
          },
          success: function (data) {
            $('.preloader').hide();
            if (data.Error == 1) {
              Swal.fire('Error!', data.Message, 'error');
            } else if (data.Error == 0) {
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Deleted!',
                text: data.Message,
                showConfirmButton: true,
                confirmButtonText: 'OK'
              }).then(result => {
                location.reload();
              });
            }
          },
          error: function () {
            $('.preloader').hide();
            Swal.fire('Error!', 'Something went wrong, please try again.', 'error');
          }
        });
      }
    });
  });
});
