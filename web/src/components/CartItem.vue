<script setup>
const emit = defineEmits(['onClickRemove'])

defineProps({
  id: Number,
  imageUrl: String,
  title: String,
  price: Number,
  isFavorite: Boolean,
  isAdded: Boolean,
  onClickFavorite: Function,
  onClickAdd: Function
})
</script>

<template>
  <div class="flex items-center border border-slate-200 p-4 rounded-xl ">
    <img class="w-16 h-16" :src="imageUrl" alt="sneaker" />
    <div class="flex justify-between gap-x-2 ms-3">
      <div class="flex-1">
        <p>{{ title }}</p>
        <b>{{ price }} руб.</b>
      </div>
      <img
        @click="emit('onClickRemove')"
        class="opacity-40 hover:opacity-100 cursor-pointer transition"
        src="/close.svg"
        alt="delete"
      />
    </div>
  </div>
</template>