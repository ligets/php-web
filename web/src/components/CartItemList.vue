<script setup>
  import CartItem from "@/components/CartItem.vue";
  import {inject} from "vue";

  const { cart, removeFromCart } = inject('cart');


</script>

<template>
  <div class="flex flex-col gap-4 flex-1 overflow-auto" v-auto-animate>
    <CartItem
        v-for="item in cart"
        :key="item.id"
        :image-url="item.imageUrl"
        :title="item.title"
        :price="item.price"
        @on-click-remove="() => removeFromCart(item)"
    />
  </div>
</template>
