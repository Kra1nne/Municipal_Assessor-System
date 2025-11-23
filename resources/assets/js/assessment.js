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

  if (absValue >= 1000000000) {
    formatted = (value / 1000000000).toFixed(2) + 'B';
  } else if (absValue >= 1000000) {
    formatted = (value / 1000000).toFixed(2) + 'M';
  } else if (absValue >= 1000) {
    formatted = (value / 1000).toFixed(2) + 'K';
  } else {
    formatted = value.toFixed(2);
  }

  return '₱' + formatted;
}

$(document).ready(function () {
  let page = 1;
  let loading = false;
  let hasMore = true;
  let searchQuery = '';
  let statusFilter = '';

  function loadProperties(reset = false) {
    if (loading || (!hasMore && !reset)) return;
    loading = true;

    if (reset) {
      $('#propertylist').empty();
      page = 1;
      hasMore = true;
    }

    $.get(
      '/assessment/lazy',
      {
        page,
        search: searchQuery,
        status: statusFilter
      },
      function (response) {
        appendProperties(response.data);
        hasMore = response.hasMore;
        page++;
        loading = false;
      }
    );
  }

  function appendProperties(properties) {
    const $list = $('#propertylist');

    if (properties.length === 0 && page === 1) {
      $list.html(`<tr><td colspan="9" class="text-center text-muted">No properties found.</td></tr>`);
      return;
    }

    properties.forEach(property => {
      let assessed_value = property.area * property.market_value_data * (property.assessment_rate / 100);

      const row = `
            <tr>
                <td>${property.parcel_id}</td>
                <td><div class="text-muted">${property.lot_number}</div></td>
                <td>${property.firstname ?? ''} ${property.lastname ?? ''}</td>
                <td>${property.area} sq. m</td>
                <td>₱ ${assessed_value.toLocaleString()}</td>
                <td>${property.ActualUse ?? ''}</td>
                <td>
                    <span class="badge bg-label-${
                      property.property_status === 'Complete'
                        ? 'success'
                        : property.property_status === 'Under Review'
                          ? 'warning'
                          : 'secondary'
                    }">
                        ${property.property_status}
                    </span>
                </td>
                <td>
                  ${
                    property.property_status === 'Complete'
                      ? `
                      <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="ri-more-2-line"></i>
                          </button>
                          <div class="dropdown-menu">
                              <a href="/assessments/pdf/${property.property_id}"
                                target="_blank"
                                class="dropdown-item assess">
                                  View
                              </a>

                              <a class="dropdown-item EditBtn"
                                  href="javascript:void(0);"
                                  data-bs-toggle="modal"
                                  data-bs-target="#EditassessmentModal"
                                  data-assessment_id="${property.assessment_id}"
                                  data-assessor_id="${property.assessor_id}"
                                  data-market_id="${property.market_value_id}"
                                  data-actual_id="${property.property_type_id}"
                                  data-sub_class="${property.sub_classification}"
                                  data-date="${property.date}"
                                  data-outlet_road="${property.outlet_road}"
                                  data-dirt_road="${property.dirt_road}"
                                  data-weather_road="${property.weather_road}"
                                  data-provincial_road="${property.provincial_road}"
                                  data-arp_no="${property.arp_no}"
                                  data-pin="${property.pin}"
                                  data-otc="${property.otc}"
                                  data-istaxable="${property.istaxable}"
                                  data-survey_no="${property.survey_no}">

                                  <i class="ri-pencil-lines me-1"></i> Edit
                              </a>
                          </div>
                      </div>
                      `
                      : `
                      <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="ri-more-2-line"></i>
                          </button>
                          <div class="dropdown-menu">
                              <a class="dropdown-item assess"
                                href="javascript:void(0);"
                                data-bs-toggle="modal"
                                data-bs-target="#assessmentModal"
                                data-property-id="${property.property_id}">
                                  Assess
                              </a>
                          </div>
                      </div>
                      `
                  }
              </td>

            </tr>`;

      $list.append(row);
    });
  }

  // Infinite scroll
  $('.table-responsive').on('scroll', function () {
    const container = this;
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 50) {
      loadProperties();
    }
  });

  // Search
  $('#search').on('input', function () {
    searchQuery = $(this).val();
    loadProperties(true);
  });

  // Status filter
  $('#statusFilter').on('change', function () {
    statusFilter = $(this).val();
    loadProperties(true);
  });

  // Initial load
  loadProperties();
});

$(document).ready(function () {
  $('body').on('click', '.assess', function () {
    const propertyId = $(this).data('property-id');

    $('#properties_id').val(propertyId);
  });

  $('body').on('click', '#AssessmentBtn', function () {
    const fields = [
      { id: 'properties_id', label: 'Property' },
      { id: 'assessor_id', label: 'Assessor' },
      { id: 'property_id', label: 'Property' },
      { id: 'market_id', label: 'Market Value' },
      { id: 'date', label: 'Date' },
      { id: 'arp_no', label: 'ARP' },
      { id: 'pin', label: 'PIN' },
      { id: 'survey_no', label: 'Survey Number' },
      { id: 'otc', label: 'OCT/TCT/CLOA' },
      { id: 'sub_classification', label: 'Sub Classification' },
      { id: 'taxable', label: 'Answerable [Yes/No]' }
    ];
    const isValid = validateForm(fields);
    if (!isValid) {
      event.preventDefault();
      return;
    }

    // ajax
    $.ajax({
      type: 'POST',
      url: '/assessment/add',
      cache: false,
      data: $('#ProperyData').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#assessmentModal').modal('hide');
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
  $('body').on('click', '.EditBtn', function () {
    const assessment_id = $(this).data('assessment_id');
    const assessor_id = $(this).data('assessor_id');
    const market_id = $(this).data('market_id');
    const actual_id = $(this).data('actual_id');
    const sub_class = $(this).data('sub_class');
    const date = $(this).data('date');
    const outlet_road = $(this).data('outlet_road');
    const dirt_road = $(this).data('dirt_road');
    const weather_road = $(this).data('weather_road');
    const provincial_road = $(this).data('provincial_road');
    const arp_no = $(this).data('arp_no');
    const pin = $(this).data('pin');
    const otc = $(this).data('otc');
    const istaxable = $(this).data('istaxable');
    const survey_no = $(this).data('survey_no');

    $('#Edit_assessment_id').val(assessment_id);
    $('#Edit_assessor_id').val(assessor_id);
    $('#Edit_market_id').val(market_id);
    $('#Edit_property_id').val(actual_id);
    $('#Edit_sub_classification').val(sub_class);
    $('#Edit_date').val(date);
    $('#Edit_dirt-road').prop('checked', dirt_road == 1);
    $('#Edit_outlet-road').prop('checked', outlet_road == 1);
    $('#Edit_weather-road').prop('checked', weather_road == 1);
    $('#Edit_provincial-road').prop('checked', provincial_road == 1);
    $('#Edit_arp_no').val(arp_no);
    $('#Edit_pin').val(pin);
    $('#Edit_otc').val(otc);
    $('#Edit_taxable').val(istaxable);
    $('#Edit_survey_no').val(survey_no);
  });
  $('#UpdateAssessmentBtn').on('click', function () {
    $.ajax({
      type: 'POST',
      url: '/assessment/update',
      cache: false,
      data: $('#ProperyDataUpdate').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#EditassessmentModal').modal('hide');
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
