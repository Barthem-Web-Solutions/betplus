// FooterStore.js
import { defineStore } from 'pinia';

export const footerStore = defineStore('footer', {
  state() {
    return {
      footerStore: false,
    };
  },

  actions: {
    setFooterToogle() {
      this.footerStore = !this.footerStore;
    },
    setFooterStatus(status) {
      this.footerStore = status;
    },
  },

  getters: {
    getFooterStatus() {
      return this.footerStore;
    },
  },
});
