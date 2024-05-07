<script setup>
import {computed, inject, ref} from "vue";

import DrawerHead from "@/components/DrawerHead.vue";
import CartItemList from "@/components/CartItemList.vue";
import InfoBlock from "@/components/InfoBlock.vue";
import axios from "axios";
import {useRouter} from "vue-router";

const router = useRouter();

const props = defineProps({
  totalPrice: Number,
  vatPrice: Number
});

const { closeDrawer, cart } = inject('cart')

const isCreating = ref(false);
const orderId = ref(false);

const createOrder = async () => {
  try {
    const token = localStorage.getItem('access_token');
    if(!token){
      return await router.push({name: 'login'})
    }
    isCreating.value = true
    const { data } = await axios.post(
        /*'https://baea1c36f2661385.mokky.dev/orders'*/
        'http://127.0.0.1:8000/api/orders', {
      items: cart.value,
      totalPrice: props.totalPrice
    }, {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`
      }
    })
    cart.value.map(item => {
      item.isAdded = false
    })
    cart.value = [];

    orderId.value = data.id;
  } catch(e){
    console.log(e)
  } finally {
    isCreating.value = false

  }
}

const cartButtonDisabled = computed(() => isCreating.value || !cart.value)


</script>

<template>
  <div @click="closeDrawer" class="fixed top-0 left-0 h-full w-full bg-black z-10 opacity-70"></div>
  <div class="flex flex-col bg-white w-96 h-full fixed right-0 top-0 z-20 p-8">
    <DrawerHead />

    <div v-if="!totalPrice || orderId" class="flex h-full items-center">
      <InfoBlock
        v-if="orderId"
        title="Заказ оформлен!"
        :description="`Ваш заказ #${orderId} скоро будет передан курьерской доставке`"
        image-url="/order-success-icon.png"
      />

      <InfoBlock
        v-else
        title="Корзина пустая"
        description="Добавьте хотя бы одну пару кроссовок, чтобы сделать заказ."
        image-url="/package-icon.png"
      />
    </div>
    <div v-else class="flex flex-col flex-1 h-4/5">
      <CartItemList />

      <div class="flex flex-col gap-y-5 mt-7">
        <div class="flex justify-between gap-x-2">
          <span>Итого:</span>
          <div class="flex-1 border-b border-gray-300 border-dashed" />
          <span class="text-black font-bold">{{ totalPrice }} руб.</span>
        </div>

        <div class="flex justify-between gap-x-2">
          <span>Налог 5%:</span>
          <div class="flex-1 border-b border-gray-300 border-dashed" />
          <span class="text-black font-bold">{{ vatPrice }} руб.</span>
        </div>

        <button
            :disabled="cartButtonDisabled"
            @click="createOrder"
            class="mt-4 bg-lime-500 w-full disabled:bg-slate-300 cursor-pointer rounded-xl py-3 text-white hover:bg-lime-600 transition active:bg-lime-700"
        >
          Оформить заказ
        </button>
      </div>
    </div>
  </div>
</template>
