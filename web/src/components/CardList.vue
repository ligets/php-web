<script setup>
  import Card from "@/components/Card.vue";

  const props = defineProps({
    items: Array,
    isFavorites: Boolean
  })

  const emit = defineEmits(['addToFavorite', 'addToCart']);

</script>
<template>
  <div class="grid grid-cols-4 gap-5" v-auto-animate>
    <Card
      v-for="item in items"
      :key="item.id"
      :id="item.id"
      :image-url="item.imageUrl"
      :title="item.title"
      :price="item.price"
      :on-click-add="isFavorites ? null : () => emit('addToCart', item)"
      :on-click-favorite="isFavorites ? null : () => emit('addToFavorite', item)"
      :is-favorite="item.isFavorite"
      :is-added="item.isAdded"
    />

  </div>
</template>
