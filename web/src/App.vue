<script setup>
import {computed, provide, ref} from "vue";
import axios from "axios";

import Header from "@/components/Header.vue";
import Drawer from "@/components/Drawer.vue";
import Home from "@/pages/Home.vue";


const cart = ref([]);

const drawerOpen = ref(false);

const totalPrice = computed(
    () => cart.value.reduce((acc, item) => acc + item.price, 0)
)

const vatPrice = computed(
    () => Math.round((totalPrice.value * 5) / 100)
)

const closeDrawer = () => {
  drawerOpen.value = false
}

const openDrawer = () => {
  drawerOpen.value = true
}

const addToCart = (item) => {
  cart.value.push(item)
  item.isAdded = true;
}

const removeFromCart = (item) => {
  cart.value.splice(cart.value.indexOf(item), 1)
  item.isAdded = false;
}

provide('cart', {
  cart,
  closeDrawer,
  openDrawer,
  addToCart,
  removeFromCart
})
</script>

<template>
  <Drawer
      v-if="drawerOpen"
      :total-price="totalPrice"
      :vat-price="vatPrice"
  />

  <div class="w-4/5 m-auto bg-white rounded-xl shadow-xl mt-12">
    <Header
        :total-price="totalPrice"
        @open-drawer="openDrawer"
    />
    <div class="p-10">
      <router-view></router-view>
    </div>
  </div>
</template>

