let displayTotal = 0;
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
// Helper: convert number to ordinal (1 -> 1st, 2 -> 2nd, 3 -> 3rd, 4 -> 4th, etc.)
function getOrdinal(n) {
  const suffixes = ['th', 'st', 'nd', 'rd'];
  const v = n % 100;
  return n + (suffixes[(v - 20) % 10] || suffixes[v] || suffixes[0]);
}
$(document).ready(function () {
  const $storeyInput = $('#storey');
  const $container = $('#storeyInputsContainer');

  $storeyInput.on('input', function () {
    const count = parseInt($(this).val());
    $container.empty(); // Clear existing inputs

    if (isNaN(count) || count <= 0) return;

    for (let i = 1; i <= count; i++) {
      const ordinal = getOrdinal(i);
      const $div = $(`
        <div class="form-floating form-floating-outline mb-3">
          <input type="text" class="form-control" name="number${i}" id="number${i}"
                 placeholder="Enter the area size of the ${ordinal} floor (sq. m.)">
          <label for="number${i}">${ordinal} Floor</label>
        </div>
      `);
      $container.append($div);
    }
  });
});

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

    $.get('/building-assessment/lazy', { page, search: searchQuery }, function (response) {
      appendProperties(response.data);
      hasMore = response.hasMore;
      page++;
      loading = false;
    });
  }

  function appendProperties(properties) {
    const $propertyList = $('#propertylist');

    if (properties.length === 0 && page === 1) {
      $propertyList.html(`<tr><td colspan="8" class="text-center text-muted">No properties found.</td></tr>`);
      return;
    }

    properties.forEach(property => {
      let ConstructionCost = property.cost ?? 0;
      let size = property.size;
      let totalsize = 0;
      if (size) {
        totalsize = size.split(',').reduce((acc, curr) => acc + parseFloat(curr), 0);
      }
      let totalAssessment = totalsize * ConstructionCost * (property.percentage / 100) ?? 0;
      displayTotal += totalAssessment;
      $('#totalValue').text(formatAbbreviatedPHP(displayTotal));
      const row = `
            <tr>
              <td>${property.parcel_id}</td>
              <td>
              <div>${property.owner}</div>
              <div class="text-muted">${property.lot_number}</div>
              </td>
              <td>${property.storey ?? 0}</td>
              <td>
                ${property.classification ?? ''}
              </td>
              <td>₱ ${totalAssessment.toLocaleString()}</td>
              <td>₱ ${ConstructionCost.toLocaleString()}</td>
               <td>
                  <span class="badge bg-label-${property.building_status === 'Complete' ? 'success' : 'warning'}">
                      ${property.building_status ?? 'Under Review'}
                  </span>
              </td>
              <td>
                ${
                  property.building_status === 'Complete'
                    ? `
                      <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="ri-more-2-line"></i>
                          </button>
                          <div class="dropdown-menu">
                              <a href="/building-assessment/pdf/${property.ids}"
                                target="_blank"
                                class="dropdown-item assess">
                                  View
                              </a>

                              <a class="dropdown-item EditBtn"
                                  href="javascript:void(0);"
                                  data-bs-toggle="modal"
                                  data-bs-target="#UpdateModal"
                                  data-property_id="${property.property_id}"
                                  data-storey="${property.storey}"
                                  data-storey_size="${property.size}"
                                  data-construction_cost="${property.cost}"
                                  data-structural_type="${property.structure_id}"
                                  data-complete="${property.complete_date}"
                                  data-roof="${property.roof}"
                                  data-flooring="${property.flooring}"
                                  data-walls="${property.walls}"
                                  data-type="${property.type}"
                                  >
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
                                data-bs-toggle="modal" data-bs-target="#AddModal" data-assessment_id="${property.assessment_id}" >
                                  Assess
                              </a>
                          </div>
                      </div>
                      `
                }
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

  $('#statusFilter').on('change', function () {
    statusFilter = $(this).val();
    loadProperties(true);
  });

  // Initial load
  loadProperties();
});

$(document).ready(function () {
  $('body').on('click', '.assess', function () {
    const assessment_id = $(this).data('assessment_id');

    $('#assessment_id').val(assessment_id);
  });

  $('#BuildingAssessment').on('click', function () {
    const fields = [
      { id: 'type', label: 'Land Classification' },
      { id: 'totalcost', label: 'Total Construction Cost' },
      { id: 'complete', label: 'Construction Complete' },
      { id: 'storey', label: 'Number of Storey' },
      { id: 'roof', label: 'Roof Materials' },
      { id: 'flooring', label: 'Flooring Materials' },
      { id: 'walls', label: 'Walls Materials' },
      { id: 'structural', label: 'Structural Type' }
    ];
    let isValid = validateForm(fields);
    if (!isValid) {
      event.preventDefault();
      return;
    }
    const storeyCount = parseInt($('#storey').val());

    if (!isNaN(storeyCount) && storeyCount > 0) {
      for (let i = 1; i <= storeyCount; i++) {
        const fieldId = `number${i}`;

        // Add this field to the array
        let storeyField = [{ id: fieldId, label: `Floor ${getOrdinal(i)} Area` }];
        isValid = validateForm(storeyField);

        if (!isValid) {
          event.preventDefault();
          return;
        }
      }
    }

    let sizes = [];
    for (let i = 1; i <= storeyCount; i++) {
      const val = $(`#number${i}`).val().trim();
      sizes.push(val);
    }

    $('#storey_size').val(sizes.join(','));

    $.ajax({
      type: 'POST',
      url: '/building-assessment/add',
      cache: false,
      data: $('#PropertyData').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#AddModal').modal('hide');
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
    const property_id = $(this).data('property_id');
    const storey = $(this).data('storey');
    const storey_size = $(this).data('storey_size');
    const construction_cost = $(this).data('construction_cost');
    const structural_type = $(this).data('structural_type');
    const complete = $(this).data('complete');
    const roof = $(this).data('roof');
    const flooring = $(this).data('flooring');
    const walls = $(this).data('walls');
    const type = $(this).data('type');

    $('#building_id').val(property_id);
    $('#Edit_totalcost').val(construction_cost);
    $('#Edit_complete').val(complete);
    $('#Edit_storey').val(storey);
    $('#Edit_type').val(structural_type);
    $('#Edit_roof').val(roof);
    $('#Edit_flooring').val(flooring);
    $('#Edit_walls').val(walls);
    $('#Edit_structural').val(type);

    const sizes = storey_size.toString().split(',');
    const $container = $('#Edit_storeyInputsContainer');
    $container.empty();

    for (let i = 1; i <= storey; i++) {
      const ordinal = getOrdinal(i);

      const $div = $(`
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" name="number${i}" id="Edit_number${i}"
                    placeholder="Enter the area size of the ${ordinal} floor (sq. m.)">
              <label for="number${i}">${ordinal} Floor</label>
            </div>
          `);

      $container.append($div);

      if (sizes[i - 1] !== undefined) {
        $('#Edit_number' + i).val(sizes[i - 1]);
      }
    }
  });

  // When user changes the storey number
  $('#Edit_storey').on('input change', function () {
    const count = parseInt($(this).val());
    const $container = $('#Edit_storeyInputsContainer');
    $container.empty();

    if (isNaN(count) || count <= 0) return;

    for (let i = 1; i <= count; i++) {
      const ordinal = getOrdinal(i);

      const $div = $(`
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" name="number${i}" id="Edit_number${i}"
                    placeholder="Enter the area size of the ${ordinal} floor (sq. m.)">
              <label for="number${i}">${ordinal} Floor</label>
            </div>
          `);

      $container.append($div);
    }
  });

  $('#BuildingAssessmentUpdate').on('click', function () {
    const storey = parseInt($('#Edit_storey').val());
    let sizes = [];
    for (let i = 1; i <= storey; i++) {
      const val = $(`#Edit_number${i}`).val().trim();
      sizes.push(val);
    }

    $('#Edit_storey_size').val(sizes.join(','));

    $.ajax({
      type: 'POST',
      url: '/building-assessment/update',
      cache: false,
      data: $('#PropertyDataUpdate').serialize(),
      dataType: 'json',
      beforeSend: function () {
        $('#UpdateModal').modal('hide');
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
