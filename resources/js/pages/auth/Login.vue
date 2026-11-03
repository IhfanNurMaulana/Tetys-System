<template>
  <v-app>
    <Head title="Log in" />
    <!-- Tambahkan d-flex align-center justify-center agar posisi presisi di tengah -->
    <v-main class="d-flex align-center justify-center" style="min-height: 100vh;">
      <v-container>
        <v-row justify="center">
          <v-col cols="12" sm="8" md="5" lg="4">
            <v-card class="pa-6" elevation="4" rounded="lg">
              <v-card-title class="text-center mb-4">
                <div class="text-h4 font-weight-bold">Tetys System</div>
              </v-card-title>

              <v-form @submit.prevent="login">
                <v-text-field
                  v-model="form.email"
                  variant="outlined"
                  label="Email"
                  type="email"
                  :error-messages="form.errors.email"
                  required
                  class="mb-2"
                />

                <v-text-field
                  v-model="form.password"
                  variant="outlined"
                  label="Password"
                  type="password"
                  required
                  class="mb-4"
                />

                <v-btn
                  type="submit"
                  color="primary"
                  size="large"
                  block
                  :disabled="form.processing"
                >
                  Login
                </v-btn>
              </v-form>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const login = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>
