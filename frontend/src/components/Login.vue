<template>
  <div class="login">
    <div class="form-header">
      <span><i class="fa-solid fa-user"></i> 1. Dane logowania</span>
    </div>
    <div>
      <button type="button" class="btnLogin" @click="openPopup">
        Logowanie
      </button>
    </div>
    <div>
      <span class="link">
        Masz już konto?<a href="#">Kliknij żeby sie zalogować.</a></span>
    </div>
    <div class="create-new-account">
      <input name="createAccount" type="checkbox" id="create-account" v-model="createAccount" />
      <label for="create-account">Stwórz nowe konto</label>
    </div>
    <div>
      <input type="text" v-model="login" placeholder="login" maxlength="255" id="login" />
    </div>
    <div>
      <input v-model="password" placeholder="Hasło" maxlength="255" autocomplete="off" :type="passwordFieldType"
        id="password" @input="checkPassword" />
      <i class="far" :class="changeIconPassword" type="password" @click="togglePassword"></i>
    </div>
    <div v-if="passwordError" class="error-message">{{ passwordError }}</div>
    <div>
      <input v-model="password_confirmation" placeholder="Potwierdź hasło" maxlength="255" autocomplete="off"
        :type="confirmPasswordFieldType" id="password_confirmation" @input="checkConfirmPassword" />
      <i class="far" :class="changeIconConfirmPassword" type="password" @click="toggleConfirmPassword"></i>
    </div>
    <div v-if="confirmPasswordError" class="error-message">{{ confirmPasswordError }}</div>

    <div>
      <input type="text" v-model="firstname" placeholder="Imię *" maxlength="255" v-on:keypress="isLetter($event)"
        required id="firstname" />
    </div>
    <div>
      <input type="text" v-model="surname" placeholder="Nazwisko *" maxlength="255" v-on:keypress="isLetter($event)"
        required id="surname" />
    </div>
    <div>
      <select v-model="country" id="country">
        <option>Polska</option>
        <option>Niemcy</option>
        <option>Holandia</option>
        <option>Hiszpania</option>
      </select>
    </div>
    <div>
      <input type="text" v-model="street" placeholder="Adres *" maxlength="255" id="street" required />
    </div>
    <div>
      <input type="text" maxlength="6" v-model="postalCode" placeholder="Kod pocztowy *" @input="formatPostalCode"
        pattern="[0-9]{2}-[0-9]{3}" id="postal-code" required />
      <input type="text" v-model="city" placeholder="Miasto *" maxlength="255 " v-on:keypress="isLetter($event)" id="city"
        required />
    </div>
    <div>
      <input type="text" v-model="phoneNumber" maxlength="9" pattern="[0-9]{9}" @input="onPhoneNumberInput"
        placeholder="Telefon *" id="phone-number" required />
    </div>

    <div class="input-adress">
      <input name="checkedOtherAddress" value="boolean" type="checkbox" id="checked-other-address"
        v-model="checkedOtherAddress" @click="toggleOtherAddress" />
      <label for="checked-other-address">Dostawa pod inny adres</label>
    </div>
    <div class="new-address" :style="{ display: changeDisplay }">
      <div>
        <select v-model="newCountry" id="new-country">
          <option>Polska</option>
          <option>Niemcy</option>
          <option>Holandia</option>
          <option>Hiszpania</option>
        </select>
      </div>
      <div>
        <input name="newStreet" type="text" v-model="newStreet" placeholder="Adres *" maxlength="255" id="new-street" />
      </div>
      <div>
        <input type="text" maxlength="6" v-model="newPostalCode" placeholder="Kod pocztowy *" @input="formatNewPostalCode"
          pattern="[0-9]{2}-[0-9]{3}" id="new-postal-code" />
        <input name="newCity" type="text" v-model="newCity" placeholder="Miasto *" maxlength="255"
          v-on:keypress="isLetter($event)" id="new-city" />
      </div>
    </div>
    <div v-if="showPopup" class="modal">
      <div class="modal-container">
        <h2>Zaloguj się</h2>
        <form>
          <div class="modal-data">
            <div>
              <button type="button" class="btnPopupClose" @click="closePopup">
                x
              </button>
            </div>
            <label for="log">Login</label>
            <input type="text" id="log" required maxlength="255" />
          </div>
          <div class="modal-data">
            <label for="psw">Hasło</label>
            <input id="psw" v-model="popupPassword" required autocomplete="off" maxlength="255" :autocomplete="off"
              :type="popupPasswordFieldType" />
            <i type="password" class="far" :class="changeIconPopupPassword" @click="togglePopupPassword"></i>
          </div>
          <div class="forgot-psw"><a href="#">Nie pamiętasz hasła? </a></div>
          <div>
            <button type="submit" class="btnPopupLogin">Zaloguj</button>
          </div>
          <div class="signup-link">
            Nie masz konta? <a href="#">Zarejestruj się</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";

export default {
  data() {
    return {
      name: "Login",
      showPopup: false,
      createAccount: '',
      login: "",
      password: "",
      passwordFieldType: "password",
      password_confirmation: "",
      confirmPasswordFieldType: "password",
      popupPassword: "",
      popupPasswordFieldType: "password",
      firstname: "",
      surname: "",
      country: "Polska",
      street: "",
      postalCode: "",
      city: "",
      phoneNumber: "",
      changeDisplay: "none",
      checkedOtherAddress: false,
      newCountry: "Polska",
      newStreet: "",
      newCity: "",
      newPostalCode: "",
      passwordError: null,
      confirmPasswordError: null,
    };
  },

  methods: {
    toggleOtherAddress() {
      this.changeDisplay =
        this.changeDisplay === "none" ? "inline-block" : "none";
    },
    formatPostalCode() {
      this.postalCode = this.postalCode.replace(/[^\d]/g, "");

      if (this.postalCode.length > 2) {
        this.postalCode =
          this.postalCode.slice(0, 2) + "-" + this.postalCode.slice(2);
      }
      this.postalCode = this.postalCode.slice(0, 7);
    },
    formatNewPostalCode() {
      this.newPostalCode = this.newPostalCode.replace(/[^\d]/g, "");

      if (this.newPostalCode.length > 2) {
        this.newPostalCode =
          this.newPostalCode.slice(0, 2) + "-" + this.newPostalCode.slice(2);
      }
      this.postalCode = this.postalCode.slice(0, 7);
    },
    onPhoneNumberInput() {
      this.phoneNumber = this.phoneNumber.replace(/\D/g, "");
    },
    openPopup() {
      this.showPopup = true;
    },
    closePopup() {
      this.showPopup = false;
    },
    isLetter(e) {
      let char = String.fromCharCode(e.keyCode);
      if (/^[A-Za-z]+$/.test(char)) return true;
      else e.preventDefault();
    },
    togglePassword() {
      this.passwordFieldType =
        this.passwordFieldType === "password" ? "text" : "password";
    },
    toggleConfirmPassword() {
      this.confirmPasswordFieldType =
        this.confirmPasswordFieldType === "password" ? "text" : "password";
    },
    togglePopupPassword() {
      this.popupPasswordFieldType =
        this.popupPasswordFieldType === "password" ? "text" : "password";
    },
    checkPassword() {
      if (this.password.length > 0 && this.password.length < 6) {
        this.passwordError = "Hasło musi mieć co najmniej 6 znaków.";
      } else {
        this.passwordError = null;
      }
    },
    checkConfirmPassword() {
      if (this.password_confirmation != this.password) {
        this.confirmPasswordError = "Hasła nie mogą się od siebie różnić.";
      } else {
        this.confirmPasswordError = null;
      }
    },
  },
  computed: {
    changeIconPassword() {
      return this.passwordFieldType === "password" ? "fa-eye-slash" : "fa-eye";
    },
    changeIconConfirmPassword() {
      return this.confirmPasswordFieldType === "password"
        ? "fa-eye-slash"
        : "fa-eye";
    },
    changeIconPopupPassword() {
      return this.popupPasswordFieldType === "password"
        ? "fa-eye-slash"
        : "fa-eye";
    },
  },
};
</script>

<style lang="scss">
@import "../assets/main.scss";
@import "../assets/color.scss";

.login {

  input,
  select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid $inputBorderFocusColor;
    border-radius: 4px;
    box-sizing: border-box;
    background-color: transparent;
  }

  input:focus {
    background-color: $inputFocusColor;
    outline: 1px solid $inputBorderFocusColor;
  }

  i.far {
    cursor: pointer;
    margin-left: -30px;
  }

  .link {
    display: flex;
    justify-content: center;

    @media screen and (min-width: 768px) {
      font-size: 13px;
    }

    @media screen and (min-width: 992px) {
      font-size: 11px;
    }
  }

  a {
    text-decoration: none;
    color: $secondaryFontColor;
    margin: 0 4px;
  }

  .create-new-account,
  .input-adress {
    display: flex;
    align-items: center;
    margin: 20px 0;
  }

  #create-account,
  #checked-other-address {
    width: 20px;
    height: 20px;
    margin-right: 10px;
  }

  .new-address {
    display: none;
  }

  .error-message {
    font-size: 11px;
    text-align: center;
    color: $errorFontColor;
    font-style: italic;
  }
}

// popup style
.modal {
  z-index: 1000;
  position: absolute;
  top: 30%;
  left: 50%;
  right: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  justify-content: center;
  width: 100%;

  .modal-container {
    background-color: $primaryFontColor;
    padding: 30px;
    box-shadow: 3px 8px 32px -15px, -8px -8px 32px -15px rgba(27, 26, 26, 0.7);
    width: 400px;

    i {
      margin-left: -30px;
      cursor: pointer;
    }

    #psw,
    #log {
      height: 100%;
      width: 100%;
      font-size: 17px;
      border: 1px solid $inputBorderFocusColor;
    }

    h2 {
      font-size: 35px;
      font-weight: 600;
      text-align: center;
    }

    .modal-data {
      position: relative;
      height: 45px;
      width: 100%;
      margin: 40px 0;
    }

    label {
      font-size: 18px;
    }

    .forgot-psw {
      text-align: left;
    }

    a {
      color: $popupForgotPswColor;
      text-decoration: none;
    }

    .signup-link {
      text-align: center;
    }
  }
}
</style>
