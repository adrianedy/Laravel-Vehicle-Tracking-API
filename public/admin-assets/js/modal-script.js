$('.ajax-form').submit(function (e) {
  e.preventDefault()
  var form = $(this)

  $.ajax({
    dataType: "json",
    url: form.attr('action'),
    method: form.attr('method'),
    data: new FormData(this),
    headers: {
      'Accept': 'application/json',
    },
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function() {
      form.find('.btn-save').addClass('disabled')
      form.find('.btn-save').addClass('btn-progress')
      form.find(':submit').prop('disabled', true)
    },
    complete: function(data) {
      form.find('.btn-save').removeClass('disabled')
      form.find('.btn-save').removeClass('btn-progress')
      form.find(':submit').prop('disabled', false)
    },
    success: function(result){
      Swal.fire(
        'Success!',
        result.data.message,
        'success'
      ).then(function() {
        if (result.data.location) window.location.hash = result.data.location

        if (result.data.redirect) {
          window.location.replace(result.data.redirect)
        } else if (result.data.debug) {
          console.log(result.data.debug)
        } else if (!result.data.norefresh) {
          location.reload(true)
        }

        if (result.data.reset) {
          form[0].reset()
        }
      })
    },
    error: function(err){
      console.log(err)
      if (err.status == 422) {
        let errors = document.createElement('ul');

        $.each(err.responseJSON.error.errors, function (i, error) {
          let item = document.createElement('li')
          item.innerHTML = error.message
          errors.appendChild(item)
        })

        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
          footer: errors
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong!',
        })
      }
    }
  })
})

$('table').on('click', '.delete-confirm-btn', function () {
    $('#delete-confirm-form').get(0).setAttribute('action', $(this).data('action'));
    $('#delete-confirmation').modal('show');
});

$('.delete-btn').on('click', function () {
    $('#delete-confirm-form').get(0).setAttribute('action', $(this).data('action'));
    $('#delete-confirmation').modal('show');
});

$('table').on('click', '.edit-btn', function () {
  let button = $(this)
  let modal = $(button.data('target'))

  $.ajax({
    dataType: "json",
    url: $(this).data('get'),
    method: 'get',
    headers: {
        'Accept': 'application/json',
    },
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function() {
      button.addClass('disabled')
      button.addClass('btn-progress')
    },
    complete: function(data) {
      button.removeClass('disabled')
      button.removeClass('btn-progress')
    },
    success: function(result) {
      $.each(result.data, function (name, data) {
        let field = modal.find('[name="' + name + '"]')

        if (field.hasClass('datepicker')) {
          field.datepicker('update', data)
          return
        }

        if (field.hasClass('daterangepicker-single')) {
          field.data('daterangepicker').setStartDate(data);
          field.data('daterangepicker').setEndDate(data);
          return
        }

        if (field.hasClass('map-form')) {
          $(`#${field.attr('id')}`).find('.map-box').locationpicker('location', {
            latitude: data[0],
            longitude: data[1]
          })
          $(`#${field.attr('id')}`).find('.loc-lat').val(data[0])
          $(`#${field.attr('id')}`).find('.loc-lng').val(data[1])
          return
        }

        if (field.prop('nodeName') == 'INPUT') {
          if (field.attr('type') == 'checkbox') {
              field.prop('checked', data)
          } else if (field.attr('type') == 'radio') {
            field.filter(`[value=${data}]`).prop('checked', true)
          } else {
              field.val(data)
          }

          if (field.hasClass('pwstrength')) {
            field.pwstrength("forceUpdate")
          }
        } else if (field.prop('nodeName') == 'TEXTAREA') {
          if (field.hasClass('ckeditor') || field.hasClass('ckeditor-image-upload')) {
            editors[field.attr('id')].setData(data)
          } else {
            field.val(data)
          }
        } else if (field.prop('nodeName') == 'IMG') {
          if (data) {
            field.attr('src', data)
          }
        } else if (field.prop('nodeName') == 'SELECT') {
          if (field.hasClass('select2-ajax')) {
            field.find('.current-option').remove()
            field.append(`<option value="${data.id}" class="current-option" selected="selected">${data.text}</option>`).trigger('change')
          } else {
            field.find(`option`).prop("selected", false).trigger('change')

            if (Array.isArray(data)) {
              field.select2().val(data).trigger('change')
            } else if (data) {
              field.find(`option[value='${data}']`).prop("selected", true).trigger('change')
            }
          }
        } else if (field.prop('nodeName') == 'SPAN' || field.prop('nodeName') == 'DIV') {
          if (field.hasClass('tab-content')) {
            field.parents('form').find('.tab-pane').removeClass('show active')
            field.parents('form').find(`[data-radio=${data}]`).addClass('show active')

            return
          }
          field.text(data)
        }
      })

      modal.modal('show')

      if (result.run) {
        window[result.run]()
      }
    },
    error: function(err){
      console.log(err)
    }
  })

  if ($(this).data('patch')) {
    modal.find('form').get(0).setAttribute('action', $(this).data('patch'))
  }
})
