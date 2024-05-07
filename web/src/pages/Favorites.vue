<script setup>
  import {onMounted, ref} from "vue";
  import axios from "axios";
  import CardList from "@/components/CardList.vue";
  import {useRouter} from "vue-router";

  const router = useRouter();

  const favorites = ref([]);

  onMounted(async () => {
    try {
      const token = localStorage.getItem('access_token');
      if(!token){
        await router.push({name: 'login'})
      }

      const { data } = await axios.get(
        /*'https://baea1c36f2661385.mokky.dev/favorites?_relations=items'*/
        'http://127.0.0.1:8000/api/favorites', {
          headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${token}`
          }
        }
      )

      favorites.value = data
    } catch(e) {
      console.log(e)
    }
  })
</script>

<template>
  <h2 class="text-3xl font-bold mb-8">Мои закладки</h2>

  <CardList
    :items="favorites"
    :is-favorites="true"
  />
</template>