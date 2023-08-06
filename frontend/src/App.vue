<template>
  <form @submit.prevent="submitForm">
    <div class="forms">
      <Login v-on:loginData="handleLoginData" 
      />
      <Methods 
        v-on:methodsData="handleMethodsData" 
        @method-selected-value="updateTotalPrice"
      />
      <Order 
        v-on:orderData="handleOrderData" 
        :total-price="totalPrice"
      />
    </div>
  </form>
</template>
<script>
import Login from "./components/Login.vue";
import Methods from "./components/Methods.vue";
import Order from "./components/Order.vue";


export default {
  components: {
    Login,
    Methods,
    Order,
  },
  data() {
    return {
      loginData: {},
      methodsData: {},
      orderData: {},
      selectedMethod: '',
      totalPrice: 100,
      previousDeliveryCost: 0
    };
  },
  methods: {
    updateTotalPrice(value) {
      const price = document.getElementById('totalPrice');
      let currentPrice = parseFloat(price.innerText);
      currentPrice -= this.previousDeliveryCost;
      this.previousDeliveryCost = parseFloat(value);
      price.innerText = (currentPrice + this.previousDeliveryCost).toFixed(2);
    },
    handleLoginData(data) {
      this.loginData = data;
    },
    handleMethodsData(data) {
      this.methodsData = data;
    },
    handleOrderData(data) {
      this.orderData = data;
    },
    submitForm() {

    }
  }
};
</script>

<style lang="scss">
@import "./assets/color";
@import "./assets/main";
@import "./assets/buttons";


.form-header {
  background-color: $primaryBgColor;
  color: $primaryFontColor;
  height: 30px;
  display: flex;
  align-items: center;
  padding: 10px;
  text-transform: uppercase;
  word-spacing: 4px;
}

form {
  .forms {
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: space-between;

    >div {
      width: 100%;
    }

    @media screen and (min-width: 768px) {
      flex-direction: row;
      flex-wrap: wrap;

      >div {
        flex: 48%;
      }
    }

    @media screen and (min-width: 992px) {
      flex-wrap: nowrap;
    }
  }
}
</style>
