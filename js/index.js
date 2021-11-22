const goToLogin = () => {
  location.href = "../login.php";
};

const goToLoginFromHome = () => {
  location.href = "./pages/login.php";
};

const goToHome = () => {
  location.href = "../index.php";
};

const goToHomeFromHome = () => {
  location.href = "index.php";
};

const goToCart = () => {
  location.href = "./cart.php";
};

const goToCartFromHome = () => {
  location.href = "./pages/cart.php";
};

const goToOrderHistory = () => {
  location.href = "./order-history.php";
};

const goToOrderHistoryFromHome = () => {
  location.href = "./pages/order-history.php";
};

const goToRequest = () => {
  location.href = "./request.php";
};

const goToRequestFromHome = () => {
  location.href = "./pages/request.php";
};

const submitData = (idx) => {
  document.forms[`itemForm-${idx}`].submit();
  return true;
};

const submitSearch = () => {
  document.forms["search-form"].submit();
  return true;
};

const getQuantity = () => {
  return document.getElementById("quantity").value;
};

const getCurrentDate = () => {
  var today = new Date();
  var date =
    today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();
  var time =
    today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  var dateTime = date + " " + time;
  return dateTime;
};

// const getQuantity = () => {
//   document.body.addEventListener("input", () => {
//     document.getElementById("quantity-hidden").value =
//       document.getElementById("quantity").value;
//   });
// };

const renderHeader = (isAdmin, isHome) => {
  var headerIcon = "";

  if (isAdmin != 1) {
    if (isHome == 0) {
      headerIcon = `
        <div class="header-option">
            <div class="header-cart" title="Keranjang" onclick="goToCart()">
                <i class="fas fa-shopping-cart"></i>
            </div>
    
            <div class="header-wishlist" title="Wishlist">
                <i class="fas fa-heart"></i>
            </div>
    
            <div class="header-chat" title="Obrolan">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>
    
        <div class="vr"></div>
    
        <div class="header-history" onclick="goToOrderHistory()">
            <i class="fas fa-history"></i>
            <p>Order History</p>
        </div>
  
        <div class="vr"></div>
        `;
    } else {
      headerIcon = `
        <div class="header-option">
            <div class="header-cart" title="Keranjang" onclick="goToCartFromHome()">
                <i class="fas fa-shopping-cart"></i>
            </div>
    
            <div class="header-wishlist" title="Wishlist">
                <i class="fas fa-heart"></i>
            </div>
    
            <div class="header-chat" title="Obrolan">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>
    
        <div class="vr"></div>
    
        <div class="header-history" onclick="goToOrderHistoryFromHome()">
            <i class="fas fa-history"></i>
            <p>Order History</p>
        </div>
  
        <div class="vr"></div>
        `;
    }
  } else {
    if (isHome == 0) {
      headerIcon = `
      
      <div class="header-history" onclick="goToOrderHistory()">
            <i class="fas fa-history"></i>
            <p>Order History</p>
        </div>
        <div class="vr"></div>
        <div class="header-add-variant" onclick="goToRequest()">
            <i class="fas fa-plus"></i>
            <p>request dorayaki</p>
        </div>
        <div class="vr"></div>
      `;
    } else {
      headerIcon = `
      <div class="header-history" onclick="goToOrderHistoryFromHome()">
            <i class="fas fa-history"></i>
            <p>Order History</p>
        </div>
        <div class="vr"></div>
        <div class="header-add-variant" onclick="goToRequestFromHome()">
            <i class="fas fa-plus"></i>
            <p>request dorayaki</p>
        </div>
        <div class="vr"></div>
      `;
    }
  }

  document.getElementById("header-user-admin").innerHTML = headerIcon;
};

// const goToProductDetails = (idx) => {
//   document.getElementsByName("itemForm")[idx].submit();
//   location.href = "product-details.php";
// };

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
// const renderAllItem = () => {
//   var xhr = new XMLHttpRequest();

//   xhr.onreadystatechange = () => {
//     if (xhr.readyState == 4 && xhr.status == 200) {
//       console.log(">>> Item ready");
//     }
//   };

//   xhr.open("GET", "ajax/database.php", true);
//   xhr.send();
// };

// const renderAllItem = () => {
//   var xhr = new XMLHttpRequest();
//   xhr.open("GET", "ajax/database.php", true);

//   var itemDat = new Array();
//   var item = {};

//   xhr.onreadystatechange = () => {
//     if (xhr.readyState == 4 && xhr.status == 200) {
//       console.log(">>> Item ready");
//     }
//   };

//   xhr.send();
// };
