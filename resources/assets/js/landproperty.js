$(document).ready(function () {
  const data = window.request;
  let totalAssessment = 0;
  let assessmentComplete = 0;
  let assessmentUnderReview = 0;
  let declineRequest = 0;
  function dataHeader(request) {
    request.forEach(request => {
      totalAssessment++;
      request.status === 'Request' ? assessmentUnderReview++ : '';
      request.status === 'Success' ? assessmentComplete++ : '';
      request.status === 'Decline' ? declineRequest++ : '';

      $('#totalRequest').text(totalAssessment);
      $('#completeRequest').text(assessmentComplete);
      $('#reviewRequest').text(assessmentUnderReview);
      $('#declineRequest').text(declineRequest);
    });
  }
  dataHeader(data);
});

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
  $('body').on('click', '#AddRequest', function (event) {
    const fields = [
      { id: 'request_form', label: 'Request form for Updated Tax Declaration' },
      { id: 'certificate', label: 'Transfer Certificate of Title/Original Certificate of Title' },
      { id: 'proff_of_transfer', label: 'Deed of Sale or any proof of transfer' },
      { id: 'authorizing', label: 'Certificate Authorizing Registration' },
      { id: 'updated_tax', label: 'Updated Real Real Property Tax Payment' },
      { id: 'transfer_tax', label: 'Transfer Tax Receipt' },
      { id: 'tax_reciept', label: 'Latest Tax Declaration' }
    ];

    const isValid = validateForm(fields);

    if (!isValid) {
      event.preventDefault();
      return;
    }
    var formData = new FormData($('#ProductData')[0]);
    $.ajax({
      type: 'POST',
      url: '/myproperty/request',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      dataType: 'json',
      beforeSend: function () {
        $('#AddRequestModal').modal('hide');
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
  function displayRequest(request) {
    const $requestList = $('#requestlist');
    $requestList.empty();

    if (request.length === 0) {
      $requestList.html(`<tr><td colspan="5" class="text-center text-muted">No request found.</td></tr>`);
      return;
    }

    request.forEach(request => {
      const requestRow = `
        <tr>
          <td>
          <div>${request.firstname} ${request.middlename ?? ' '} ${request.lastname}</div>
          <div>${request.email ?? ''}</div>
          </td>
          <td><span class="badge rounded-pill ${request.status === 'Success' ? 'bg-label-success' : request.status === 'Request' ? 'bg-label-warning' : 'bg-label-danger'}">${request.status}</span></td>
          <td>Requested of the Tax Declaration of the Property</td>
          <td>${new Date(request.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
          <td>
            <a target="_blank"
              class="btn ${request.status === 'Success' ? 'btn-success' : request.status === 'Request' ? 'btn-warning disabled-link' : 'btn-danger'}"
              href="${request.status === 'Success' ? `/myproperty/pdf/${request.encrypted_assessment_id}` : '#'}"
            >
              ${request.status === 'Success' ? 'View' : request.status === 'Request' ? 'Pending' : 'Decline'}
            </a>
          </td>
        </tr>
      `;
      $requestList.append(requestRow);
    });
  }

  function filterProperties(query) {
    const filtered = window.request.filter(request => {
      const fullName = `${request.firstname} ${request.lastname}`.toLowerCase();
      return fullName.includes(query) || request.email.toLowerCase().includes(query);
    });
    displayRequest(filtered);
  }

  $('#search').on('input', function () {
    const query = $(this).val().toLowerCase();
    filterProperties(query);
  });

  // Render all properties on page load
  displayRequest(window.request);
});
