<script setup>

  const props = defineProps({
    id: Number,
    imageUrl: String,
    title: String,
    price: Number,
    isFavorite: Boolean,
    isAdded: Boolean,
    onClickAdd: Function,
    onClickFavorite: Function
  })

</script>

<template>
  <div class="relative flex flex-col bg-white border-slate-100 rounded-3xl p-8 cursor-pointer hover:-translate-y-2 hover:shadow-xl transition">
    <img
      v-if="onClickFavorite"
      @click="onClickFavorite"
      class="absolute top-8 left-8"
      :src="isFavorite ? '/like-2.svg' : '/like-1.svg'"
      alt="like 1"
    />
    <img :src="imageUrl" alt="sneaker" />

    <p class="mt-2 flex-1">{{ title }}</p>

    <div class="flex justify-between mt-5">
      <div class="flex flex-col">
        <span class="text-slate-400">Цена: </span>
        <b>{{ price }} руб.</b>
      </div>
      <img
        v-if="onClickAdd"
        @click="onClickAdd"
        :src="isAdded ? '/checked.svg' : '/plus.svg'"
        alt="plus"
      />
    </div>
  </div>
</template>