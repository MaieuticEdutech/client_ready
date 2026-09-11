import { initCountUp } from './count-up'
import { initPlayer } from './player'
import { initReveal } from './reveal'

const boot = () => {
    initReveal()
    initCountUp()
    initPlayer()
}

boot()
document.addEventListener('livewire:navigated', boot)
