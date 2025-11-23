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

function formatAbbreviatedPHP(value) {
  value = parseFloat(value) || 0;

  const absValue = Math.abs(value);
  let formatted = '';

  if (absValue >= 1_000_000_000) {
    formatted = (value / 1_000_000_000).toFixed(2) + 'B';
  } else if (absValue >= 1_000_000) {
    formatted = (value / 1_000_000).toFixed(2) + 'M';
  } else if (absValue >= 1_000) {
    formatted = (value / 1_000).toFixed(2) + 'K';
  } else {
    formatted = value.toFixed(2);
  }

  return '₱' + formatted;
}

$(document).ready(function () {
  $('#AddAccountBtn').on('click', function (event) {
    const fields = [
      { id: 'owner', label: 'Owner Name' },
      { id: 'lot_number', label: 'Lot Number' },
      { id: 'address', label: 'Address' },
      { id: 'type', label: 'Type' },
      { id: 'area', label: 'Area' },
      { id: 'longitude', label: 'Longitude' },
      { id: 'latitude', label: 'Latitude' },
      { id: 'previous_owner', label: 'Previous Owner' },
      { id: 'street', label: 'Street' },
      { id: 'brgy', label: 'Barangay/District' },
      { id: 'south', label: 'South' },
      { id: 'north', label: 'North' },
      { id: 'west', label: 'West' },
      { id: 'east', label: 'East' }
    ];

    const isValid = validateForm(fields);

    if (!isValid) {
      event.preventDefault();
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/properties/add',
      cache: false,
      data: $('#ProperyData').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#AddAccount').modal('hide');
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
  let page = 1;
  let searchQuery = '';
  let loading = false;
  let hasMore = true;

  function loadProperties(reset = false) {
    if (loading || (!hasMore && !reset)) return;
    loading = true;

    if (reset) {
      $('#propertylist').empty();
      page = 1;
      hasMore = true;
    }

    $.get('/properties/lazy', { page, search: searchQuery }, function (response) {
      appendProperties(response.data);
      hasMore = response.hasMore;
      page++;
      loading = false;
    });
  }

  function appendProperties(properties) {
    const $propertyList = $('#propertylist');

    if (properties.length === 0 && page === 1) {
      $propertyList.html(`<tr><td colspan="7" class="text-center text-muted">No properties found.</td></tr>`);
      return;
    }

    properties.forEach(property => {
      const row = `
            <tr>
                <td>${property.parcel_id ?? 'N/A'}</td>
                <td>${property.lot_number ?? 'N/A'}</td>
                <td>${property.property_address ?? ''}</td>
                <td>${property.property_type ?? ''}</td>
                <td>${property.area} sq. m</td>
                <td>
                    <div>${property.longitude}&deg; E</div>
                    <div>${property.latitude}&deg; N</div>
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ri-more-2-line"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item Edit"
                                data-bs-toggle="modal"
                                data-bs-target="#EditPropertyData"
                                data-id="${property.property_id}"
                                data-lot_number="${property.lot_number}"
                                data-address="${property.property_address}"
                                data-owner="${property.owner}"
                                data-type="${property.property_type}"
                                data-area="${property.area}"
                                data-longitude="${property.longitude}"
                                data-latitude="${property.latitude}"
                                data-street="${property.street}"
                                data-brgy="${property.brgy}"
                                data-previous_owner="${property.previous_owner}"
                                data-north="${property.north}"
                                data-south="${property.south}"
                                data-west="${property.west}"
                                data-east="${property.east}"
                                data-tin="${property.tin}">
                                <i class="ri-pencil-line me-1"></i>Edit
                            </a>

                            <a class="dropdown-item DeleteBtn" data-id="${property.property_id}">
                                <i class="ri-delete-bin-6-line me-1"></i>Delete
                            </a>
                        </div>
                    </div>
                </td>
            </tr>`;
      $propertyList.append(row);
    });
  }

  // Lazy loading on scroll
  $('.table-responsive').on('scroll', function () {
    const container = this;
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 50) {
      loadProperties();
    }
  });

  // Lazy search
  $('#search').on('input', function () {
    searchQuery = $(this).val();
    loadProperties(true); // Reset & reload
  });

  // Initial load
  loadProperties();
});

// for editing
$(document).ready(function () {
  $('body').on('click', '.Edit', function () {
    const id = $(this).data('id');
    const owner = $(this).data('owner');
    const lot_number = $(this).data('lot_number');
    const address = $(this).data('address');
    const type = $(this).data('type');
    const area = $(this).data('area');
    const longitude = $(this).data('longitude');
    const latitude = $(this).data('latitude');
    const previous_owner = $(this).data('previous_owner');
    const street = $(this).data('street');
    const brgy = $(this).data('brgy');
    const north = $(this).data('north');
    const south = $(this).data('south');
    const west = $(this).data('west');
    const east = $(this).data('east');
    const tin = $(this).data('tin');

    $('#Edit_id').val(id);
    $('#Edit_owner').val(owner);
    $('#Edit_lot_number').val(lot_number);
    $('#Edit_address').val(address);
    $('#Edit_type').val(type);
    $('#Edit_area').val(area);
    $('#Edit_longitude').val(longitude);
    $('#Edit_latitude').val(latitude);
    $('#Edit_previous_owner').val(previous_owner);
    $('#Edit_street').val(street);
    $('#Edit_brgy').val(brgy);
    $('#Edit_north').val(north);
    $('#Edit_south').val(south);
    $('#Edit_west').val(west);
    $('#Edit_east').val(east);
    $('#tin').val(tin);
  });

  $('body').on('click', '#EditBtn', function (event) {
    const fields = [
      { id: 'Edit_owner', label: 'Owner Name' },
      { id: 'Edit_lot_number', label: 'Lot Number' },
      { id: 'Edit_address', label: 'Address' },
      { id: 'Edit_type', label: 'Type' },
      { id: 'Edit_area', label: 'Area' },
      { id: 'Edit_longitude', label: 'Longitude' },
      { id: 'Edit_latitude', label: 'Latitude' },
      { id: 'Edit_previous_owner', label: 'Previous Owner' },
      { id: 'Edit_street', label: 'Street' },
      { id: 'Edit_brgy', label: 'Barangay/District' },
      { id: 'Edit_south', label: 'South' },
      { id: 'Edit_north', label: 'North' },
      { id: 'Edit_west', label: 'West' },
      { id: 'Edit_east', label: 'East' }
    ];

    const isValid = validateForm(fields);

    if (!isValid) {
      event.preventDefault();
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/properties/update',
      cache: false,
      data: $('#UpdatedPropertyData').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#EditPropertyData').modal('hide');
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

//for deleting
$(document).ready(function () {
  $('body').on('click', '.DeleteBtn', function () {
    const id = $(this).data('id');
    console.log(id);
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: '/properties/delete',
          cache: false,
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
      }
    });
  });
});
