<script setup>
import {reactive, ref} from "vue";
import { useRouter } from 'vue-router';
import axios from "axios";

const router = useRouter();

const isSubmit = ref(false);

const conditional = reactive({
  login: '',
  password: '',
  password_confirmation: ''
})

const errors = reactive({
  login: '',
  password: ''
});

const submit = async () => {
  try {
    isSubmit.value = true;
    const { data, status } = await axios.post('http://127.0.0.1:8000/api/auth/register', conditional, {
      headers: {
        Accept: 'application/json'
      }
    })
    if(status === 200){
      localStorage.setItem('access_token', data.access_token);
      await router.push({name: 'home'})
    }
  }catch (e) {
    if(e.response.status === 422) {
      errors.login = e.response.data.errors.login
      errors.password = e.response.data.errors.password;
    }
  } finally {
    isSubmit.value = false
  }
}

</script>

<template>
  <div class="flex flex-col items-center">
    <form class="flex flex-col w-4/12" @submit.prevent="submit">
      <div v-if="errors.login" class="flex flex-col">
        <span v-for="item in errors.login"
            class="text-red-600"
        >{{ item }}</span>
      </div>

      <input v-model="conditional.login" type="text" class="border border-gray-400 rounded-md outline-none px-3 mb-2" placeholder="Логин" required />
      <div v-if="errors.password" class="flex flex-col">
        <span v-for="item in errors.password"
              class="text-red-600"
        >{{ item }}</span>
      </div>
      <input v-model="conditional.password" type="password" class="border border-gray-400 rounded-md outline-none px-3 mb-2" placeholder="Пароль" required />
      <input v-model="conditional.password_confirmation" type="password" class="border border-gray-400 rounded-md outline-none px-3" placeholder="Подтвердите пароль" required />
      <button
        :disabled="isSubmit"
        type="submit"
        class="mt-4 mx-auto bg-lime-500 disabled:bg-slate-300 px-3 py-1 cursor-pointer rounded-2xl text-white hover:bg-lime-600 transition active:bg-lime-700"
      >
        Регистрация
      </button>
    </form>
  </div>
</template>