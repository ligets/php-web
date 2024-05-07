<script setup>
  import CardList from "@/components/CardList.vue";
  import axios from "axios";
  import debounce from 'lodash.debounce';
  import {inject, onMounted, reactive, ref, watch} from "vue";
  import {useRouter} from "vue-router";

  const router = useRouter();

  const items = ref([]);

  const { cart, addToCart, removeFromCart } = inject('cart')

  const filters = reactive({
    searchQuery: '',
    sortBy: 'title',
  });

  const addToFavorite = async (item) => {
    try {
      const token = localStorage.getItem('access_token');
      if(!token){
        await router.push({name: 'login'})
      }
      if(!item.isFavorite){
        const obj = {
          item_id: item.id
        }
        item.isFavorite = true
        const { data } = await axios.post(
          /*'https://baea1c36f2661385.mokky.dev/favorites'*/
          'http://127.0.0.1:8000/api/favorites', obj, {
            headers: {
              Accept: 'application/json',
              Authorization: `Bearer ${token}`
            }
          }
        )

        item.favoriteId = data.id
      }
      else {
        item.isFavorite = false
        await axios.delete(
          /*`https://baea1c36f2661385.mokky.dev/favorites/${item.favoriteId}`*/
          `http://127.0.0.1:8000/api/favorites/${item.id}`, {
            headers: {
              Accept: 'application/json',
              Authorization: `Bearer ${token}`
            }
          }
        )
        item.favoriteId = null
      }

    } catch (e) {
      console.log(e)
    }
  }

  const onClickAddPlus = (item) => {
    if (!item.isAdded){
      addToCart(item);
    }
    else {
      removeFromCart(item);
    }
  }

  const onChangeSelect = (event) => {
    filters.sortBy = event.target.value;
  }

  const onChangeSearchInput = debounce((event) => {
    filters.searchQuery = event.target.value
  }, 500)

  const fetchFavorites = async () => {
    try {
      const token = localStorage.getItem('access_token');
      if(!token){
        return;
      }
      const { data: favorites } = await axios.get(
        /*'https://baea1c36f2661385.mokky.dev/favorites'*/
        'http://127.0.0.1:8000/api/favorites', {
          headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${token}`
          }
        }
      )
      items.value = items.value.map(item => {
        const favorite = /*favorites.find(favorite => favorite.item_id === item.id)*/ favorites.find(favorite => favorite.id === item.id);
        if (!favorite) {
          return item;
        }

        return {
          ...item,
          isFavorite: true,
          favoriteId: favorite.id
        };
      });
    } catch (e){
      console.log(e)
    }
  }

  const fetchItems = async () => {
    try{
      const params = {
        sortBy: filters.sortBy,
      }

      if (filters.searchQuery) {
        params.title = `*${filters.searchQuery}*`;
      }

      const { data } = await axios.get(
          /*`https://baea1c36f2661385.mokky.dev/items`*/
          'http://127.0.0.1:8000/api/items', {
            params
          }
      )
      items.value = data.map((obj) => ({
        ...obj,
        isFavorite: false,
        isAdded: false,
        favoriteId: null
      }))
    } catch (e) {
      console.log(e)
    }
  }

  onMounted(async () => {

    const localCart = localStorage.getItem('cart')
    cart.value = localCart ? JSON.parse(localCart) : [];

    await fetchItems();
    await fetchFavorites();

    items.value = items.value.map((item) => ({
      ...item,
      isAdded: cart.value.some((cartItem) => cartItem.id === item.id)
    }));
  })

  watch(filters, fetchItems);

  watch(cart, () => {
    localStorage.setItem('cart', JSON.stringify(cart.value))
  }, { deep: true })

</script>

<template>
  <div class="flex justify-between items-center">
  <h2 class="text-3xl font-bold mb-8">Все кросовки</h2>


  <div class="flex items-center gap-4">
    <select @change="onChangeSelect" class="py-2 px-3 border rounded-md outline-none">
      <option value="title">По названию</option>
      <option value="price">По цене(дешёвые)</option>
      <option value="-price">По цене(дорогие)</option>
    </select>

    <div class="relative">
      <img class="absolute left-4 top-3" src="/search.svg" alt="search" />
      <input
          @input="onChangeSearchInput"
          class="border rounded-md py-2 pl-11 pr-4 outline-none focus:border-gray-400"
          placeholder="Поиск..."
          type="text"
      />
    </div>
  </div>

  </div>
  <div class="mt-10">
  <CardList
    :items="items"
    @add-to-favorite="addToFavorite"
    @add-to-cart="onClickAddPlus"
  />
  </div>
</template>