import { initReveal } from './reveal'

const boot = () => initReveal()

boot()
document.addEventListener('livewire:navigated', boot)
