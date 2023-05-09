import { defineStore } from 'pinia'

export const userAuth = defineStore('auth', {
  state: () => ({
    currentUser: null
  }),
  actions: {
    setCurrentUser(currentUser) {
      this.currentUser = currentUser
    }
  },
})