<template>
  <div class="summary">
    <div class="form-header">
      <span><i class="fa-solid fa-clipboard-list"></i> 4. Podsumowanie</span>
    </div>
    <div>
      <div>
        <div class="test-product" v-for="product in products" :key="product.id">
          <div class="product-data">
            <img
              :src="`${this.backend_api}${product.image_path}`"
              height="50"
              width="90"
              alt="Logo produktu"
            />
            <div>
              <div class="product-name">{{ product.name }}</div>
              <div>Ilość: {{ amount }}</div>
            </div>
          </div>
          <div class="product-price">{{ product.price }} zł</div>
        </div>
      </div>
    </div>
    <div class="subtotal-price">
      <span>Suma częściowa</span><span>115,00zł</span>
    </div>

    <div class="total-price">
      <span>Łącznie</span><span id="totalPrice">{{ totalPrice }}</span>
    </div>

    <div>
      <textarea
        v-model="textareaValue"
        rows="5"
        cols="50"
        placeholder="Komentarz"
      ></textarea>
    </div>
    <div class="input-newsletter" :style="{ display: hiddenNewsletter }">
      <input type="checkbox" id="newsletter" v-model="inputNewsletter" @click="openNewsletter" :disabled="isNewsletterOpen"/>
      <label for="newsletter">Zapisz się, aby otrzymywać newsletter</label>
    </div>
    <div class="input-statute">
      <div class="input-statute-contener">
        <input
          type="checkbox"
          id="input-statute"
          v-model="inputStatute"
          name="statute"
          required
        />
        <label for="input-statute"
          >Zapoznałam/em się z <a href="#">Regulaminem</a> zakupów</label
        >
      </div>
      <div
        class="show-validate-message"
        v-if="showValidationMessage && !inputStatute"
        
      >
        Musisz zaakceptować regulamin
      </div>
    </div>
    <div class="confirm-button">
      <button type="submit" class="btnConfirm" @click="handleSubmit" >
        Potwierdź zakup
      </button>

      <div class="response" v-if="orderResponse">
        <div class="response-content">
          <i :class="orderResponse.checkIcon"></i>
          <div class="thanks">{{ orderResponse.message }}</div>
          <div class="number">{{ orderResponse.message2 }}</div>
          <button class="back-homepage" @click="goToHomePage">
            {{ orderResponse.button }}
          </button>
        </div>
      </div>
    </div>

    <div v-if="checkDeliveryMethods" class="not-selected-delivery">
      <p>Nie wybrano metody dostawy.</p>
    </div>
    <div v-if="checkPaymentMethods" class="not-selected-payment">
      <p>Nie wybrano metody płatności.</p>
    </div>

    <div
      class="g-recaptcha"
      data-sitekey="6LdCImknAAAAAH-1SDmeK7Mg3KigMQfXUKccg1yq"
      data-theme="dark"
      @verify="onRecaptchaVerify"
    >
    </div>

    <div class="newsletter-form" v-if="showNewsletter">
    <p class="heading"> Zapisz się na nasz Newsletter! </p>
    <form class="form">
    <div>
      <button type="button" class="btnNewsletterClose" @click="closeNeswletter">
        x
      </button>
      </div>
      <input placeholder="Wpisz swój adres e-mail" name="email" id="email" type="email" maxlength="255" v-model="email" required>
      <button type="button" @click="sendEmailAddress" class="btnNewsletterConfirm">Zapisz się</button>
    </form>
  </div>

  </div>
</template>
<script>
import axios from "axios";
import Methods from "./Methods.vue";

export default {
  components: {
    Methods,
  },
  props: ["totalPrice"],
  data() {
    return {
      name: "Order",
      showNewsletter: false,
      textareaValue: "",
      inputNewsletter: false,
      inputStatute: false,
      products: [],
      amount: "1",
      orderResponse: "",
      totalPrice: null,
      showValidationMessage: false,
      checkDeliveryMethods: false,
      checkPaymentMethods: false,
      hiddenNewsletter: 'flex',
      isNewsletterOpen: false,
      email: '',
      backend_api: import.meta.env.VITE_APP_BACKEND,
    };
  },
  methods: {
    sendEmailAddress() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (this.email && emailRegex.test(this.email)) {
        this.showNewsletter = false;
        this.hiddenNewsletter = 'none';
      } else {
        const emailInput = document.querySelector('input[name="email"]');
        emailInput.placeholder = 'Nie wpisano adresu e-mail';
        emailInput.style.border = '1px solid red';
      }
    },
    openNewsletter() {
      this.showNewsletter = true;
      this.inputNewsletter = true;
      this.isNewsletterOpen = true;
    },
    closeNeswletter() {
      this.showNewsletter = false;
      this.inputNewsletter = false;
      this.isNewsletterOpen = false;
    },
    updateTotalPrice(price) {
      const productPrice = this.products[0].price;
      this.totalPrice = productPrice + price;
    },
    formatPostalCode() {
      this.postalCode = this.postalCode.replace(/[^\d]/g, "");

      if (this.postalCode.length > 2) {
        this.postalCode =
          this.postalCode.slice(0, 2) + "-" + this.postalCode.slice(2);
      }
      this.postalCode = this.postalCode.slice(0, 7);
    },
    async handleSubmit() {

      this.showValidationMessage = true;
      if (!this.inputStatute) {
        return;
      }
      this.showValidationMessage = false;

      if (!document.querySelector('input[name="deliveryMethod"]:checked')) {
        this.checkDeliveryMethods = true;
      } else {
        this.checkDeliveryMethods = false;
      }

      if (!document.querySelector('input[name="paymentMethod"]:checked')) {
        this.checkPaymentMethods = true;
      } else {
        this.checkPaymentMethods = false;
      }

      const apiUrl = `${this.backend_api}/api/place-order`;

      let bodyFormData = new FormData();
      bodyFormData.append("login", document.getElementById("login").value);
      bodyFormData.append("password", document.getElementById("password").value);
      bodyFormData.append("password_confirmation", document.getElementById("password_confirmation").value);
      bodyFormData.append("firstname", document.getElementById("firstname").value);
      bodyFormData.append("surname", document.getElementById("surname").value);
      bodyFormData.append("country", document.getElementById("country").value);
      bodyFormData.append("street", document.getElementById("street").value);
      bodyFormData.append("postalCode", document.getElementById("postal-code").value);
      bodyFormData.append("city", document.getElementById("city").value);
      bodyFormData.append("phoneNumber", document.getElementById("phone-number").value);
      bodyFormData.append("inputStatute",document.getElementById("input-statute").value);

      // NEW ADDRESS
      bodyFormData.append("newCountry", document.getElementById("new-country").value);
      bodyFormData.append("newStreet", document.getElementById("new-street").value);
      bodyFormData.append("newPostalCode", document.getElementById("new-postal-code").value);
      bodyFormData.append("newCity", document.getElementById("new-city").value);

      // RADIO INPUT
      bodyFormData.append("deliveryMethod", document.querySelector('input[name="deliveryMethod"]:checked').value);
      bodyFormData.append("paymentMethod", document.querySelector('input[name="paymentMethod"]:checked').value);

      // CHECKBOX
      bodyFormData.append("createAccount", document.querySelector('input[name="createAccount"]').checked);
      bodyFormData.append("checkedOtherAddress", document.querySelector('input[name="checkedOtherAddress"]').checked);


      try {
        const response = await axios.post(apiUrl, bodyFormData, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
        this.orderResponse = response.data;
      } catch (error) {
        console.error(error);
        alert("Wystąpił błąd podczas składania zamówienia. Spróbuj ponownie.");
      }
    },
    goToHomePage() {
      window.location.href = "/";
    },
  },
  created() {
    axios
      .get(`${this.backend_api}/api/products`)
      .then((response) => {
        this.products = response.data;
        this.totalPrice = this.products[0].price;
      })
      .catch((error) => {
        console.error("Błąd podczas pobierania danych:", error);
      });
  },
};
</script>

<style lang="scss">
@import "../assets/main.scss";

.summary {
  textarea {
    width: 100%;
    height: 80px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    resize: none;
    margin: 10px 0 0 0;
    overflow: scroll;

    &:focus {
      outline: 1px solid $inputBorderFocusColor;
    }
  }

  .input-newsletter,
  .input-statute {
    position: relative;
    display: block;
    align-items: center;
    margin: 20px 0;

    @media screen and (min-width: 992px) {
      font-size: 12px;
    }

    .input-statute-contener {
      display: flex;
      align-items: center;
    }

    .show-validate-message {
      position: absolute;
      top: 70%;
      left: 10%;
      margin-top: 10px;
      margin-bottom: 20px;
      font-size: 12px;
      font-style: italic;
      color: $errorFontColor;
    }
  }

  .input-newsletter {
    display: flex;
  }

  #newsletter,
  #input-statute {
    width: 20px;
    height: 20px;
    margin-right: 10px;
  }

  .subtotal-price,
  .total-price {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin: 20px 0 8px 0;

    span {
      margin: 0 20px;
    }
  }

  .total-price {
    border-bottom: 3px dotted $marginDottedColor;
    padding: 0 0 8px 0;
    font-size: 23px;
    font-weight: 800;

    @media screen and (min-width: 992px) {
      font-size: 20px;
      border-bottom: 2px dotted $marginDottedColor;
    }
  }

  .subtotal-price {
    border-top: 3px dotted $marginDottedColor;
    padding: 8px 0 0 0;

    @media screen and (min-width: 992px) {
      font-size: 12px;
      border-top: 2px dotted $marginDottedColor;
    }
  }

  .confirm-button {
    padding-top: 10px;

    @media screen and (min-width: 768px) {
      display: flex;
      justify-content: center;
    }
  }

  .test-product {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;

    .product-data {
      height: 60px;
      display: flex;
      gap: 10px;
    }

    .product-name {
      font-weight: 600;
      margin-bottom: 10px;
    }

    .product-price {
      height: 60px;
      font-weight: 500;
    }
  }
  .newsletter-form {
    z-index: 100;
  width: 450px;
  height: 170px;
  margin: 0 auto;
  padding: 20px;
  border: 2px solid #333;
  border-radius: 8px;
  background-color: #f7f7f7;
  box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
  position: absolute;
  top: 30%;
  left: 50%;
  right: 50%;
  transform: translate(-50%, -50%);
  
  .heading {
    font-weight: bold;
    text-align: center;
    font-size: 20px;
  }

  h2 {
    margin-top: 0;
    color: #333;
    font-size: 24px;
  }

  label {
    display: none;
  }

  input[type="email"] {
    width: 100%;
    padding: 10px 0;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    text-align: center;
    margin-top: 10px;
  
  }
  
}
  .g-recaptcha {
    margin-top: 20px;
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .not-selected-delivery,
  .not-selected-payment {
    color: $errorFontColor;
    font-style: italic;
    text-align: center;
    animation: fadeInAnimation 1s ease;

    @keyframes fadeInAnimation {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  }
}

.response {
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 32%;
  left: 50%;
  right: 50%;
  transform: translate(-50%, -50%);
  width: 70%;
  background-color: white;
  height: 40%;
  padding: 50px;
  border-radius: 10px;
  border-top: 30px solid $errorFontColor;
  box-shadow: 3px 8px 32px -15px, -8px -8px 32px -15px rgba(27, 26, 26, 0.7);

  @media screen and (min-width: 768px) {
    width: 70%;
    height: 50%;
  }

  .response-content {
    text-align: center;

    i {
      color: $errorFontColor;
      font-size: 50px;
      margin-bottom: 30px;
    }

    .thanks {
      font-size: 40px;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .number {
      font-size: 20px;
      margin-bottom: 50px;
    }
  }
}
</style>
