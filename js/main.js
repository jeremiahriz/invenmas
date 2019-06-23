$(() => {
  $('[data-toggle="tooltip"]').tooltip();
  $('.stockitems').submit(e => {
    e.preventDefault();
    console.log(document.activeElement)
  });
  $('#uploadPicture input').change(function () {
    alert("A file has been selected.");
  });
})

var editUserID = null

function showNewUpload() {
  console.log(document.getElementById('newUploadInput'))
  document.getElementById('newUploadInput').focus()
}

function showEdit(index) {
  editUserID = index
  getUser(index).then(resp => {
    resp = JSON.parse(resp)
    $('#editUser input[name = "firstname"]').val(resp.firstname)
    $('#editUser input[name = "lastname"]').val(resp.lastname)
    $('#editUser input[name = "email"]').val(resp.email)
    $('#editUser select[name = "role"]').val(resp.role).change()
  })
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

function addUser() {
  complete = false
  const data = $('#addNewUser').serializeArray();
  let obj = {}
  data.forEach(element => {
    if (!element.value) {
      complete = false
      alert("All fields are required")
    }
    else {
      obj[element.name] = element.value
      complete = true
    }
  });
  if (complete) {
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
  complete = false
  const data = $('#editUser').serializeArray();
  let obj = {}
  data.forEach(element => {
    if (!element.value) {
      complete = false
      alert("All fields are required")
    }
    else {
      obj[element.name] = element.value
      complete = true
    }
  });
  obj['id'] = editUserID
  if (complete) {
    $.ajax({
      type: "POST",
      url: "./editUser.php",
      data: obj
    }).done(msg => {
      if (msg) alert(msg)
      location.reload();
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
    console.log(resp)
    $('#editItemSKU').text(resp.sku)
    $('#editItem input[name = "editname"]').val(resp.name)
    $('#editItem input[name = "edittag"]').val(resp.tag)
    $('#editItem input[name = "editquantity"]').val(resp.quantity)
    $('#editItem input[name = "editminquantity"]').val(resp.min_quantity)
    $('#editItem input[name = "editmaxquantity"]').val(resp.max_quantity)
  })
}

function editItem() {
  complete = false
  const data = $('#editItem').serializeArray();
  let obj = {}
  data.forEach(element => {
    if (!element.value) {
      complete = false
      alert("All fields are required")
    }
    else {
      obj[element.name] = element.value
      complete = true
    }
  });
  obj['id'] = editUserID
  if (complete) {
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
  let complete = false
  const data = $('#addNewItem').serializeArray();
  let obj = {}
  data.forEach(element => {
    if (!element.value) {
      complete = false
      alert("All fields are required")
    }
    else {
      obj[element.name] = element.value
      complete = true
    }
  });
  if (complete) {
    $.ajax({
      type: "POST",
      url: "./newItem.php",
      data: obj
    }).done(msg => {
      if (msg) {
        $('#showItemModal').click();
      }
      else {
        alert('There was an error adding your item')
      }
    });
  }
}