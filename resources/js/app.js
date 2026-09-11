import { initCountUp } from './count-up'
import { initReveal } from './reveal'

const boot = () => {
    initReveal()
    initCountUp()
}

boot()
document.addEventListener('livewire:navigated', boot)
