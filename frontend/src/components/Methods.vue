<template>
  <div class="methods">
    <div class="form-header">
      <span><i class="fa-solid fa-truck"></i> 2. Metody dostawy</span>
    </div>
    <div>
      <div
        v-for="method in deliveryMethods"
        :key="method.id"
        class="input-text"
      >
        <input
          name="deliveryMethod"
          type="radio"
          :id="`delivery-${method.id}`"
          :value="method.id"
          v-model="selectedItem1"
          @click="selectedMethod"
          :data-cost="method.price"
          class="input-radio"
          @change="handleDeliveryMethodChange"
        />
        <label class="input-delivery-methods" :for="`delivery-${method.id}`">
          <span class="delivery-methods-data">
            <img
              :src="`${this.backend_api}${method.image_path}`"
              width="55"
              height="40"
              alt="Logo dostawy"
            />
            {{ method.name }}
          </span>
          <span class="delivery-methods-price">{{ method.price }} zł </span>
        </label>
      </div>
    </div>
    <div class="form-header">
      <span><i class="fa-solid fa-credit-card"></i> 3. Metoda płatności</span>
    </div>
    <div>
      <div v-for="method in paymentMethods" :key="method.id" class="input-text">
        <input
          name="paymentMethod"
          type="radio"
          :id="`payment-${method.id}`"
          :value="method.id"
          v-model="selectedItem2"
          class="input-radio"
        />
        <img
          :src="`${this.backend_api}${method.image_path}`"
          width="50"
          height="30"
          alt="Logo metody płatności"
        />
        <label :for="`payment-${method.id}`">{{ method.name }}</label>
      </div>
    </div>
    <div>
      <div
        class="button-discount"
        v-if="!selectedItem1"
        @mouseover="showTooltip = true"
        @mouseleave="showTooltip = false"
      >
        <div v-if="showTooltip" class="tooltip">
          Aby dodać kod rabatowy wybierz metodę dostawy
        </div>
        <button type="button" class="btnDiscount" @click="toggleDisplay">
          Dodaj kod rabatowy
        </button>
      </div>
      <div v-else>
        <button type="button" class="btnDiscount" @click="toggleDisplay">
          Dodaj kod rabatowy
        </button>
      </div>
    </div>

    <div
      class="discount"
      :style="{ display: displayStyle }"
      v-if="!discountAdded"
    >
      <input
        type="text"
        v-model="discountCode"
        placeholder="Wprowadź kod rabatowy"
      />
      <button class="add-discount" @click="checkDiscount">Dodaj</button>
    </div>

    <div v-if="response">
      <div class="add-discount-message">{{ response.message }}</div>
      <div class="add-discount-message" v-if="response.discountPercent">
        Rabat: {{ response.discountPercent }}%
      </div>
    </div>
    <div v-if="this.errorMessage" class="error-message">
      {{ this.errorMessage }}
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      name: "Methods",
      deliveryMethods: [],
      paymentMethods: [],
      selectedItem1: null,
      selectedItem2: null,
      displayStyle: "none",
      discountCode: "",
      response: "null",
      discountAdded: false,
      errorMessage: "",
      showTooltip: false,
      backend_api: import.meta.env.VITE_APP_BACKEND,
    };
  },
  methods: {
    handleDeliveryMethodChange() {
      this.$emit("deliveryMethodSelected", this.selectedDeliveryMethod);
    },
    selectedMethod(method) {
      const cost = method.target.dataset.cost;
      this.$emit("method-selected-value", cost);
    },
    toggleDisplay() {
      if (this.selectedItem1) {
        this.displayStyle = this.displayStyle === "none" ? "flex" : "none";
      } else {
        this.showTooltip = true;
      }
    },
    async checkDiscount() {
      let apiRes = null;
      const apiUrl = `${this.backend_api}/api/discount-code`;
      try {
        const response = await axios.post(apiUrl, {
          discount_code: this.discountCode,
        });

        this.response = response.data;
        this.discountAdded = true;
        this.errorMessage = "";
        const discountPercent = parseFloat(response.data.discountPercent);
        const price = document.getElementById("totalPrice");
        let currentPrice = parseFloat(price.innerText);
        console.log(discountPercent, currentPrice);
        price.innerText = (
          currentPrice -
          (currentPrice * discountPercent) / 100
        ).toFixed(2);
      } catch (error) {
        apiRes = error.response;
        this.errorMessage = apiRes.data.message;
      }
    },
  },
  async created() {
    try {
      const deliveryResponse = await axios.get(
        `${this.backend_api}/api/delivery-methods`
      );
      this.deliveryMethods = deliveryResponse.data;

      const paymentResponse = await axios.get(
       `${this.backend_api}/api/payment-methods`
      );
      this.paymentMethods = paymentResponse.data;
    } catch (error) {
      console.error("Błąd podczas pobierania danych z API", error);
    }
  },
};
</script>

<style lang="scss">
@import "../assets/main.scss";
@import "../assets/buttons.scss";

.methods {
  .input-text {
    font-size: 12px;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin: 10px 0;

    .input-radio {
      width: 30px;
      height: 20px;
      margin: 12px;
    }

    img {
      margin-left: -8px;
      margin-right: 10px;
    }

    .input-delivery-methods {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;

      .delivery-methods-data {
        display: flex;
        align-items: center;
      }

      .delivery-methods-price {
        font-weight: 500;
      }
    }
  }

  .discount {
    width: 100%;
    display: none;
    justify-content: space-around;
    height: 50px;
    margin-top: 10px;

    input {
      width: 80%;
      box-sizing: border-box;
      height: 50px;
      margin: 0;
      border-radius: 2px;
      border: 2px solid $inputBorderFocusColor;
      border-right: none;
      position: relative;
      font-size: 14px;
      padding: 0 10px;
    }

    input:focus {
      background-color: none;
      outline: none;
    }

    input::placeholder {
      position: absolute;
      text-align: center;
      font-style: italic;
    }
  }

  .error-message {
    font-size: 17px;
    display: flex;
    justify-content: center;
    font-style: italic;
    margin-top: 8px;

    @media screen and (min-width: 768px) {
      font-size: 14px;
    }

    @media screen and (min-width: 992px) {
      font-size: 12px;
    }
  }

  .add-discount-message {
    display: block;
    text-align: center;
    box-sizing: border-box;
    padding: 2px;
    font-size: 14px;
    border-radius: 25px;
  }

  .button-discount {
    position: relative;
  }

  //  tooltip style
  .tooltip {
    position: absolute;
    top: 100%;
    left: 20%;
    margin-top: 11px;
    background-color: $btnConfirm;
    color: $primaryFontColor;
    border-radius: 7px;
    padding: 10px 15px;
    width: 60%;
    text-align: center;
    font-weight: 600;
    letter-spacing: 2px;
    font-size: 12px;

    &::before {
      content: "";
      position: absolute;
      left: 50%;
      bottom: 100%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-bottom: 20px solid $btnConfirm;
      border-left: 20px solid transparent;
      border-right: 20px solid transparent;
    }
  }
}
</style>
