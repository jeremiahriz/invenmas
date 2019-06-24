$(() => {
  $('[data-toggle="tooltip"]').tooltip();
  $('.stockitems').submit(e => {
    e.preventDefault();
    console.log(document.activeElement)
  });
  $('#newUploadInput').change(e => {
    addImage(e.target.files[0])
  });
})

var editUserID = null

function showNewUpload() {
  document.getElementById('newUploadInput').focus()
}

function reduce(index) {
  $.ajax({
    type: "GET",
    url: "./reduce.php",
    data: { id: index }
  }).done(msg => {
    location.reload();
  });
}

function add(index) {
  $.ajax({
    type: "GET",
    url: "./add.php",
    data: { id: index }
  }).done(msg => {
    location.reload();
  });
}

function addImage(image) {
  console.log(image)
  let fData = new FormData();
  fData.append('newImage', image);
  $.ajax({
    type: "POST",
    url: "./addImage.php",
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    cache: false,
    data: fData
  }).done(msg => {
    if (msg) alert(msg)
    else location.reload();
  });
}

function deleteUser(index) {
  $.ajax({
    type: "GET",
    url: "./deleteUser.php",
    data: { id: index }
  }).done(msg => {
    if (msg) alert(msg)
    else location.reload();
  });
}

function deleteImage(index) {
  $.ajax({
    type: "POST",
    url: "./deleteImage.php",
    data: { id: index }
  }).done(msg => {
    if (msg) alert(msg)
    else location.reload();
  });
}

function getUser(index) {
  return $.ajax({
    type: "GET",
    url: "./getUser.php",
    data: { id: index }
  }).done(msg => {
    return msg
  });
}

function setUser(index) {
  editUserID = index
  let respo = getUser(index).then(resp => {
    console.log(resp)
    resp = JSON.parse(resp)
    $('#editUser input[name = "firstname"]').val(resp['firstname'])
    $('#editUser input[name = "lastname"]').val(resp['lastname'])
    $('#editUser input[name = "email"]').val(resp['email'])
    $('#editUser select[name = "role"]').val(resp['role']).change()
  })
}

function addUser() {
  const data = $('#addNewUser').serializeArray();
  let obj = {}
  let values = []
  data.forEach(element => {
    obj[element.name] = element.value
    if (element.value) values.push(element.value)
  });
  if (Object.keys(obj).length === values.length) {
    $.ajax({
      type: "POST",
      url: "./addUser.php",
      data: obj
    }).done(msg => {
      if (msg) alert(msg)
      else
        location.reload();
    });
  }
}

function editUser() {
  const data = $('#editUser').serializeArray();
  let obj = {}
  let values = []
  data.forEach(element => {
    obj[element.name] = element.value
    if (element.value) values.push(element.value)
  });
  if (Object.keys(obj).length === values.length) {
    obj['id'] = editUserID
    $.ajax({
      type: "POST",
      url: "./editUser.php",
      data: obj
    }).done(msg => {
      if (msg) alert(msg)
      else {
        alert("Changes saved successfully")
        location.reload();
      }
    });
  }
}

function getItem(index) {
  return $.ajax({
    type: "GET",
    url: "./getItem.php",
    data: { id: index }
  }).done(msg => {
    return msg
  });
}

function setItem(index) {
  editUserID = index
  getItem(index).then(resp => {
    resp = JSON.parse(resp)
    $('#editItemSKU').text(resp.sku)
    $('#editItem input[name = "editname"]').val(resp.name)
    $('#editItem input[name = "edittag"]').val(resp.tag)
    $('#editItem input[name = "editquantity"]').val(resp.quantity)
    $('#editItem input[name = "editminquantity"]').val(resp.min_quantity)
    $('#editItem input[name = "editmaxquantity"]').val(resp.max_quantity)
  })
}

function editItem() {
  const data = $('#editItem').serializeArray();
  let obj = {}
  let values = []
  data.forEach(element => {
    obj[element.name] = element.value
    if (element.value) values.push(element.value)
  });
  if (Object.keys(obj).length === values.length) {
    obj['id'] = editUserID
    $.ajax({
      type: "POST",
      url: "./editItem.php",
      data: obj
    }).done(msg => {
      if (msg) alert(msg)
      location.reload();
    });
  }
}


function addNewItem() {
  const data = $('#addNewItem').serializeArray();
  let obj = {}
  let values = []
  data.forEach(element => {
    obj[element.name] = element.value
    if (element.value) values.push(element.value)
  });
  if (Object.keys(obj).length === values.length) {
    $.ajax({
      type: "POST",
      url: "./newItem.php",
      data: obj
    }).done(msg => {
      if (msg == 'success') {
        $('#showItemModal').click();
      }
      else {
        alert('There was an error adding your item')
      }
    });
  }
  else {
    alert("All fields are required")
  }
}