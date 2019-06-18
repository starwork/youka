<template>
  <div class="tabbar">
    <van-tabbar v-model="active" active-color="#00BD71" @change="changeTabBar">
      <van-tabbar-item>
        <span>首页</span>
        <img slot="icon"
             slot-scope="props"
             :src="props.active ? homeIcon.active : homeIcon.normal"
             alt="home"/>
      </van-tabbar-item>
      <van-tabbar-item :info="cart_num">
        <span>购物车</span>
        <img slot="icon"
             slot-scope="props"
             :src="props.active ? cartIcon.active : cartIcon.normal"
             alt="cart"/>
      </van-tabbar-item>
      <van-tabbar-item :info="message_num">
        <span>消息</span>
        <img slot="icon"
             slot-scope="props"
             :src="props.active ? newsIcon.active : newsIcon.normal"
             alt="news"/>
      </van-tabbar-item>
      <van-tabbar-item>
        <span>我的</span>
        <img slot="icon"
             slot-scope="props"
             :src="props.active ? userIcon.active : userIcon.normal"
             alt="user"/>
      </van-tabbar-item>
    </van-tabbar>
  </div>
</template>

<script>
import {getTarBarNum} from '../api/data'
export default {
  name: 'TabBar',
  components: {
  },
  data() {
    return {
			cart_num:'',
			message_num:'', 
      active: 0,
      homeIcon: {
        normal: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAkCAYAAADl9UilAAACnklEQVRYhe3YW6hUVRzH8Y82oRmGbwoaHiSRQPEKiVAKKpL0oIEZnkUXEa+VZBSFL/VSYXJeDmUgCtGSUNSiNy9RoIWaJ6GHHizwQenBSxFadrwVC9fIcZjBmTN7xpf5vezZe/b6r+9a67/+///aQ7q7uzWpIXgDr2M0+vAKfqzHbIyx6vOhTUKNwtf4CH9hFybje6xvxnAzYJPzrDyDTzANL2I6TuNjfI6H2wn2PI5jHF7CBlzL/yWoJ/AFAo5hYqvBSujJnV7AHHxW5b2/sQKvYRJOYkmrwJJjH85OfhAzcOoebXrxFC7jS2zBA0WCpaX5CXPxPhbjjzrbHsuD+BZvuj240UWArcURjMRSbMbNOqHKOo+F+BDz8iDnDBZsOHZiG37DLHzVINBApcG8k30t7dTvsLHWy7UC7Hjsw0zsxcu40gRUpR7DfkzBbqyKMd5lv9qMLchTneLRW3iuYCh5BWbnOLc8hZ4QwqRaYCm1vI0DGJG3e4ro/xUMVdY/eAHr8gyeDCEsqwQbmZfsA/ya/WtYi4DuUozxUzyJP7EnhNATQiglsMdxAs9iB16tgG4H3IkcUg7lOHkgdf4DJmB1ckJcbxdQBdxFPI13caOUHfxUThv3VTHGFFLek3Pf9vsNVE2lAmwkd3hksI1DCOWfV2OM/eWbIsB6my0Ks87h0fJNEWBj0Z/z4GCV0tTUgW2LAEv6N++mhpVq/hBCVyVY22JVo+qANaoOWKPqgDWqDlijqhb5y7D9ddo6ilvtALuRrw/WaWNrgTx3VG0pz+TrlFZ0WEPpZH7pXmBnczW7El2tJgohjMmHkb6Bz2tVF5vwDX7Ox7nfW3SMS6ezVOc/VFk21QJL3yrm51JmUTbQCqVN8wvWxBjTR5fbwv+KoY04Z8ZgcQAAAABJRU5ErkJggg==',
        active: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAkCAYAAADl9UilAAADhUlEQVRYhb2YaYiNURjHf+9d3GnsS00i5gODGtlHoyyFRD4gW+bNliQGITsTUySEsn2QLa9EtkaUXbbL2EJNkeWDkkbGTGOYe+fOvXo4r+6M987c5fD/cG/3vOc893ee9znPOc8x8vLySFEGsAxYAmQAT4B84FE8Zi3Lcmx3pQjVCigCtgEVwHEgG7gHzE/FcCpg2corY4F9QG9gBtAHeA3sBY4BTf8n2FTgIdARmAksAILqmUANBE4AJvAA6PqvwTzADvWnn4FBwFGHflXANGAR0A14DIz7V2AS2NdUkF8B+gLPGhmzGxgCVALngK2AWyeYvJqnwFBgMzAGKItz7AM1iZvAcn5PLkMH2DzgDtAcGA+sBWrjhLJVCowEtgDD1CQHJQuWBhwC9gNvgP7A+QSBoiWTWa1iTVbqLWBxrM6xEmxn4AzQDzgNzAK+pQBVX12As0BP4CQwx7KsOvadPDZCuVry0QpgsmYoAoW5nQJrct6Gs9vK6p2CQbFpmt1igcnWsgq4DKSr5S4ZPaIRKCdQmHvhV+JNc+fUTM6qCI3OrMAwsiSlmKY5ye7rUd8S2EeACcArlXt8GoHaAeuBifWf1ea2r4p0aFbjPVJSSyh8yjTNnfKmxGM9gGIFdRBYqMakuo/aUKNUqvgLyla4U/NgcFFvyY1XVZ68LB67r17dXOCAWs46gCQ0VkZNtEFFWvn6q8WwTlKJRwX4M7VtaFGgMNettq6YXnJQS8uyJKVsRMXYAV1AUdqcIBRq2/ojT8N945LEYgu7YzC/1zh+hKYnbMXt8usG2x19KGyy53lyVryusRzWC9ZBwkr2wXBW6wGRjPTBiRpwvfqaZpR+bxfdpgNMVA1sqDG7F9WPlXjkLQ+4jdLv3jqwmsBsdddlSDdYUud7J+kGC+sypBvsky5DusFe6jKkG+y2LkO6wS4BIR2GtIL5CvxSdFzUYUu3x0S7kqii/pITmN0WiNPGXeCG/cNX4H8dozpPSE5bkh0j3jgNbXdo2wQMTubOwpaTx96r757JGvUV+KvViaMqrgHfauTzS2NgH9RpdjaQmQJciapHqxvqZ1QGXa4PlU3Uhd8fxTpdLAWuAy9UOfcxmTLOV+An0iatOJzZYjgeQ+qKugqEDdebch+hsNQHcn3QKJjcVQyXowwgVY6Ud0nJKKvGXRbDaQYRPK53UhlZliWV1G8BPwH+69ure7CEzwAAAABJRU5ErkJggg=='
      },
      cartIcon: {
        normal: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAjCAYAAAD48HgdAAADW0lEQVRYhc2YeYhPURTHP7/Z7Mw02QqFFCZL1rITUZYoUeZZk/nHGkpKKUMxioyGMozGtaTmL2uJsYxsGSHbIFlmMHYJo1l06vz0PO/9Ft5vfr71a+bdd999n3vuOeee+wKZmZnEWX2APGAg8A7YAmxIijNUW+A00BQ4CmQA2UCNHawJMBMYB3QCmscA5AswHniq18uAVGCqMabIsqzGwC1pT9AO/YH7wA5gApAGfPT5l6AW6WgDHat/xVoYY74CR8SCYrHWwAlAaFcCe4D3MbDWHKDA0SbW6WmMqbK1rQJykhQmXcwJFMUAKJRqnPcUskLMOxkoiwNUSAlYZ+DG/wSFgom+xxPCsqyAsy3BvWtMlKKD1jkHN8b80eaVYKW9nY90rYAsdfYHkTzgBbZLw9tvrQFe/QvYPuCTj1AfgJPAlUgf8AI7o7+4qT6dPyp5WayB7p9+Vx+yF16NpKPXixfJfuUv0y+NAorDdfIC2w/8iMFSSyK/ZLtuGA4sxdH+EtjmM5SbugDlbjfEIpWOGqm+1AHoDZz3AivWert7PUIlAtv1/XleYJuAWq0iB8UYKKDvkBw5Ecg3xpS4dRQfKwVmAHuBi8Bz4KEO4qfzp+rypelGLhZb6tU56PyHgcvAYmAM0NdHoKC+AY+AC2qE26E629PFM2CF7Tpdw/k1UO0DWKKeL6r0/BhSbks1RQ8Jb4EXGrU5elj5G8nk1usEy3Xcu8D0UGM5E+xyYDPwGSjU49toteRwYIRuK5FKtrZTwBA9VxwAmunkD2key3Yby26xDI3QO0BXYDawBOih7bJ3ro3SYqsVKhfopj48V4Ek6NZZltUvHNgCvZ6lmT+oWj3ryfLOV1+JRAEds0yjr9b2zBsp9fX/rHBgvRSo1KVfneY5CfX2EYJJOd0GOOaACuoe8FgOvOHAqsOUOcF7kUZo8ICRHKJPsgf0b2DXgZbASA8ocViJrIoIwSr148kkDQKnBmjCvRYObKeWOgWOfbMRsFsdNtdrhh7aqi8v1K9JQclYB9X6rnulfemeAPN0kJtAiSbCYWrJ48DGKKBEMpGhwDQtEM8BLTT1SBBlGWPkK9MfciZYKRAHa+6RikOWTxKiRJUsSbQ7gJwjBWqhBpaMJ+njrLpMvutTwE+wK8gkDmg9UwAAAABJRU5ErkJggg==',
        active: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAjCAYAAAD48HgdAAAE50lEQVRYhcWYa2wUVRSAv3t3drv0vaAFQxWwFAIkGMUHVRP0h4mvIPiOra/EYGLUaHzEaGK0UX6IxhdRE1GJDlFLiCYYMTHoDzUhUTRiIKUFBESUhra02y6d7s5cc7ZTWLo73UepnGSTnZlz73z33HPuOWdUc3MzZ1guAt4BLgO6gdeB1dYZhjoH2ApUAl8Bi4CXADcTrAK4G7gWOB+ongSQAeAG4IB//ThQC9xq2/amlpaWcmCH3Ne+wiVAO/AucCMQA46d5p/2LTLnBGZY3+T/E2th23YC2CwWFItNB7YAQvsU8CHQMwnWug/4aPTCaW26N/x5x0y9s1uAnAy9Z4A1lg8zTcwJbJoEoFMlGrKcZy99M/0+rdTYxz7kYQFbAXT8L1BAckXDi8DsfHqy7w3Ab5MNZGZU1KX/lFnzC9Efdf6hyYRyWpsWu0vqHg163tLSkrWlOrfqaYVqAtow6eNIxIzVsW07617QASv36ycKlVrZcLHqGXpNDSTLQ9uPVKAVpm5KqpCxQWDv++E9IbG+2HvKcHdZfdxURbyJgH0C9JUKZWLROq+h5nq0P3/U8rzGmOPNqhoudI4gsO/8X9HitDZdCHwKDJa6ME638zutTXL0fAxUTXSuIIuV+fmz4OrD1FfG9O7e1Vj67EClsDbeeYVtZ9CLH5F8VSiUiDo0QHhDe1695D0Lur25tU4+vSCwDcBwQVutlXaXzrjTVITn5dW1NN6s6pMWS3lZB+tYsMiY+/8Ab+V9kfjVC0ufA2aW4uyqZ8girHNWMmKRrlNqpCLEaW1aCTxUyljV64RUVyKMZ7YGgX3v19sLi4QS/VdLgcIzWJv31aSTk2ty7oyAvSKqfhV5eYFQchysk6OzKCADen9/JPzBzrP0nmMydp1t2z/mUhUf+xW4C1gP/AT8BXSKtYOcP7L29/kmGppaDJMacpXqd0IMudpP5GuBx4L0R52/DdgGSGlyDbBk3Jd0JYT6eDFgjOjLon/wjfDHeMqZx8VB4MmM62n+Vh2RwC4SIpeE/P7C8fvHcSXXVq30W6ijwCE/atf4zUopIot72V/g3/68u4A7CrWYyBPpSAvrpDc/Nmiilqc7e7XqGxZLLgOuAhJFwElq+xa40lRF4t68WFwNu1q398wl6X0GNPoNbpZkfiKQnm+HmRZNJe9f1GuqI276rgFry5/loW3/SmMqlnu6CDBpPJ53l9T1pZY3DOKf82ogqcPrd8VUVyKdk23b/mXswMytXCXXyVsa+05AMRKbqevmJExtmTStD/i+UogIxiqxVCZUeq2VYS95W+NovfdgrrkywS4wFWHX1Fcms+0K3sKpoisd+rkFgklXNMNbEIMcGdFML0+Z2jIJqsX5wFIYE5hU8TL0CpORBsPN6jNOysiznKV2Jth2lUhpvedYWTaUQe/sFmiJrMMFgkk0H9DtvaFcVYQ+GI+o+LAE38/5wN5DkbS+3FujjiRORmvSU+GNnVUqPixp6O2gFQbIG2owWR5u66hm2D0Bp44et6yNnTUoXP/bWJaM/XDXnC6NtVJefaXDFMvT+/tDOK5Y8WtAvs4Uc9hKoEhWuZmykOPNrnZxXC3Wkl0Qx7dtW3LuuBYTkQLxCjzzjT4YN3p3bxTH3efntOUlZACxyO3Awzhuh8yn9/eLa0ipc7VfCGQL8B9fy5j+cEXUVgAAAABJRU5ErkJggg=='
      },
      newsIcon: {
        normal: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAjCAYAAAD48HgdAAACj0lEQVRYhd3YT4iVVRjH8U93RoWgVMShFmOK5DYxYUKJNv5BcBXBhPMiCOLCRUkqiCC4aRG58g9EgaC+im6kFomL0JXlIkqkFEMQp02o2K2Fppgjj503LsOd3nec9947+YPDhfPe95zv+5zzPOd5zgsjIyNatAxbsAqL0NB5NfEdjuJUnudjMWMBNhOHsDlhXMFPuN9hrH4MYgizcA7v5nn+RwF2EsP4Btvwcxcs9VR5nsuybC724oMEtyqI1yeoE8gw1i2oFrjf8WGWZY/wETY00p56GA96ATVOexCQWxtpfS/gTo+hwnL30nYaCrABjPYaqkUB19eNcPBM6p/ES+HW8zA7wkxN89/EjXYPqoCFp36MBTXBtOqufz520mDhGMfwKz7Hb8lr6tL1icYpAxtOv2txtUagUpVt/li+B7jWTSgVwMKif+Fxl3j+1f86XERomNMpgCzLxndFplMKFmfnyzV7YiWVge1PQbDbGi0DO59axxT5WDs9F2dlV5Rl2dtYPe3AsClaI+U/A9MAqNCrUQQ1Uun0Dub3mijLshexEj8E2KeIjtN4pYdQfTiAl/BFUb7tw/ZUR36NX3B73Lt/pxSoWWGemOQtzKjw3wBZkvK+pfgyasti8+/At9iF9/5jkD9xpGSihTiOFRWgWtVMVdInUY23euXp1OJcfG1c+rw8zFshpX4fn6X0Oyr77ysAjaX0+mKe5w+LznbhotlmucoO8ViOg9iIW1iHsxWgJoz8dcSx5emKYTHOpDh0a6qDTuVIimXdmfbmYKrk19cBZQoWG0jXRmtT2h1761IdQIWexWJrcDlBHcabdUOZhMWKy5bdeD2FjbDSqbqBClW12I/J9eOW8Su80UkoeALJL4msXkJ67wAAAABJRU5ErkJggg==',
        active: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAjCAYAAAD48HgdAAADa0lEQVRYhcWYW4hNURjHf3vvc3EdxrjmMoakqFHu44EXl9S8KNdmUsqlPAyF0mikKQ+TRhElSmG7PRAPhMiDawjJJfcYYRBjrs6Zc/bWN63NdBz2PrftX7vTWfvba//2t9b61vctraysjE4aD6wEZgJFgE7u1QDcAA4Cx03TtOWNDlgI2A0sVxiPgYdAW46xAnbvcInWFC0ibmuEjBtE43NN0/zugB0DFgEXgbXAIx88RaS6ZAWwRWuN6caltz2N2/XdCehXiFkzjOLi4lJgK3AEmAd88glqOrBTRo2gbluj8yNaa0zT65pGAc91NaeiwBrA9gkqD9iROIdjswqbCBtxYLXcmAJcA774AaW0Cej/R2tIt62iXuKkKboyeOsXUaS6RIZqyV8NgrqMmuFHOEhUhbzYzSiQQodDgQKgV8eETUP2kB75+ouGhWhaB5idH47ZfbrE0wUrV6t2WDownaW9ayZ48Mnvli4BK1I56WM6YLIwDgF1wF5AOvmWLlh8+uBVdtgY5Py3C7om9ZYXsEXqdw7Q6VNTV6S6pC+wAWjx8rDb5JfhiwBPM2ByNC0VYzcw8egPwMoYC8amYuxnuBiTirGXVSmhoXf6PKqTlvZCW9fcHSFZhgcwicJ5maxER6GaOynZu4HJ7v8mUyhRfPKA5R7MOmTcqt/sBnZZXRkrVjpigdc+jlbUbv8fe6Un+QkW9QT06nuovLy82k+wBi9Gxt1P3YAqAWtNmrRlX1899dgUlUjQpqvSaQbQL8dg7qs7aml6XXMQuCtg2wBx30lgYA7BXv7zrmUTPPHcJmZJKblPwsV5oBZYB7wCzgDPgM8Jj0qKcsjjXJFEcKokyk5D4MKbmDU8rxld64GmhVWzTWt7o/6hpU2/9zlPa2mXZPSUFL9OHFsPXAc2AvP/8cJG4IAL1HDgcGI2YVx933ElUV/VJB9cBdRINd45wJ5Ul+yLhQnp80Rxr4eUejGwR6XfUtl72Ydksr8Gbpqm+SukJIv8DUmGy20T7wnsApaqgnkucM4DFKZpJm1PpRj5myaqI4aRwFlgWTaq+UwCrKZS5euqgpJKvjRbRwzpeqy/Ojaao9JumVv3swHkKB2PzQYeKKj9wIRsQ5GCx5zDlkpASnwJG+Kl49kGcuTVY/fU0pdTxtPAuFxCAfwEeOrkprOR928AAAAASUVORK5CYII='
      },
      userIcon: {
        normal: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAkCAYAAADl9UilAAADXklEQVRYhcWYaYiNURjHfzP2JWuyjTCDpGQnXwgRCVkS5siWLZRpijKILF8kfCChKC8hawnFZBlryhIylmQJMROGUrbo0f/qutzrfe/7mvnX7TRzz/ue33mec57lZuTm5pKmagFjgGFAD6AFkAF8AoqBImCfxqTyPO+vX1VOg6kSMAdYCjTU/54D54EPQCOgA9AJmAtcAeYDl4MskhkQyhYtBDZoU6uAtkAW0AcYCvQE6gEDgcNAL+ACsDjIQkEsZlBnZA1bcCbwOsncr8ApfQzY/LVCFs7zs5hfi5n79gtqIzAqBVSizslqxXLprCjB5mrnR4B5wHefz8X0EhgMvAXWAi2jALPbtwQoA2akARXTE2ABUBNYFAXYaJ0Nc6Ff9yXTDuAxMBGoHRZsmMadIaHQpdgF1AAGhAXrBpTo8Eahs3pH17BgzYCHEUGZHmlskWqSH7Bq0fD80jc/a/sBK1FwjUoNNJalmuQH7B6QHZcXwyp2tu6GBTuteUMiAhupsTDVJD9guzTmq6wJo/ba4FXgQVgwc+VBoLMSd7rKUJC2cfW/3uE3V+ar1loPtEsTzKqL/sAxVSeRgMXSyDugfkAgq0zWAQWKYZP8PBSkULTKookqUr/qF1fB3tffpX6eTae0jpdtrCnQXJG8taqRxkBf1W8oz877V+wKC9YFGKHdd1cZk0wlOk8bg0AFAbN51k7lqclAqeUWcAd4BtQFWum7KrKkhYfp+txQkbhHVUZosMFqPuw2fgEOALuBk7qpqVQHGARMAIbLpQWq+0+kejDV4bezsh04DuQAm4A26iUP+oAyvVevMEppbau6KnvnFtVlgcCsHbsETAau6VxZL/nUB0wyPVVpbs3xTbn3onMuyy9YG0F1BDYDvXWWotJ1dU3blE2KnHN/NCeJYI3l+yw1ILOBzxFCxfRJFlumC1PonPuteokHq6zfGnLUYa/8D0CJWg6s0Zr7nHOV/ga2UL3jIVmrvGTrHlUezU8Ey9GPJC+AaSF6x8DyPM/WmgK8Mgs651rFg5nrqor4bTla66c8zytVM1w9doQydQvHKjLvLW+oOFnwvQ2Mc85lZyq2ZOgQlpsLEyWXrlGZNNXAxivBHqgoqDhZlrCMMsHA3iiQWmypUHme9/FnjwFVfgBxybQFe9FBwwAAAABJRU5ErkJggg==',
        active: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAkCAYAAADl9UilAAADXklEQVRYhcWYaYiNURjHfzP2JWuyjTCDpGQnXwgRCVkS5siWLZRpijKILF8kfCChKC8hawnFZBlryhIylmQJMROGUrbo0f/qutzrfe/7mvnX7TRzz/ue33mec57lZuTm5pKmagFjgGFAD6AFkAF8AoqBImCfxqTyPO+vX1VOg6kSMAdYCjTU/54D54EPQCOgA9AJmAtcAeYDl4MskhkQyhYtBDZoU6uAtkAW0AcYCvQE6gEDgcNAL+ACsDjIQkEsZlBnZA1bcCbwOsncr8ApfQzY/LVCFs7zs5hfi5n79gtqIzAqBVSizslqxXLprCjB5mrnR4B5wHefz8X0EhgMvAXWAi2jALPbtwQoA2akARXTE2ABUBNYFAXYaJ0Nc6Ff9yXTDuAxMBGoHRZsmMadIaHQpdgF1AAGhAXrBpTo8Eahs3pH17BgzYCHEUGZHmlskWqSH7Bq0fD80jc/a/sBK1FwjUoNNJalmuQH7B6QHZcXwyp2tu6GBTuteUMiAhupsTDVJD9guzTmq6wJo/ba4FXgQVgwc+VBoLMSd7rKUJC2cfW/3uE3V+ar1loPtEsTzKqL/sAxVSeRgMXSyDugfkAgq0zWAQWKYZP8PBSkULTKookqUr/qF1fB3tffpX6eTae0jpdtrCnQXJG8taqRxkBf1W8oz877V+wKC9YFGKHdd1cZk0wlOk8bg0AFAbN51k7lqclAqeUWcAd4BtQFWum7KrKkhYfp+txQkbhHVUZosMFqPuw2fgEOALuBk7qpqVQHGARMAIbLpQWq+0+kejDV4bezsh04DuQAm4A26iUP+oAyvVevMEppbau6KnvnFtVlgcCsHbsETAau6VxZL/nUB0wyPVVpbs3xTbn3onMuyy9YG0F1BDYDvXWWotJ1dU3blE2KnHN/NCeJYI3l+yw1ILOBzxFCxfRJFlumC1PonPuteokHq6zfGnLUYa/8D0CJWg6s0Zr7nHOV/ga2UL3jIVmrvGTrHlUezU8Ey9GPJC+AaSF6x8DyPM/WmgK8Mgs651rFg5nrqor4bTla66c8zytVM1w9doQydQvHKjLvLW+oOFnwvQ2Mc85lZyq2ZOgQlpsLEyWXrlGZNNXAxivBHqgoqDhZlrCMMsHA3iiQWmypUHme9/FnjwFVfgBxybQFe9FBwwAAAABJRU5ErkJggg=='
      }
    }
  },
  methods: {
    /**
     * 点击路由跳转
     * @param active
     */
    changeTabBar(active) {
      const self = this;
      self.active = active;
      switch (active) {
        case 0:
          self.$router.push({
            path: '/home'
          });
          break;
        case 1:
          self.$router.push({
            path: '/cart'
          });
          break;
        case 2:
          self.$router.push({
            path: '/news'
          });
          break;
        case 3:
          self.$router.push({
            path: '/user'
          });
          break;
      }
    }
  },
  beforeCreate() {

  },
  created() {
    const self = this;
		getTarBarNum().then(res => {
			self.cart_num = res.data.cart_num > 0 ? res.data.cart_num : '';
			self.message_num = res.data.message_num > 0 ? res.data.message_num : ''
		})
    // 确定路由位置
    switch (self.$route.path) {
      case '/home':
        self.active = 0;
        break;
      case '/cart':
        self.active = 1;
        break;
      case '/news/notice':
        self.active = 2;
        break;
      case '/user/center':
        self.active = 3;
        break;
    }
  },
	activated(){
		const self = this;
		getTarBarNum().then(res => {
			self.cart_num = res.data.cart_num > 0 ? res.data.cart_num : '';
			self.message_num = res.data.message_num > 0 ? res.data.message_num : ''
		})
		// 确定路由位置
		switch (self.$route.path) {
		  case '/home':
		    self.active = 0;
		    break;
		  case '/cart':
		    self.active = 1;
		    break;
		  case '/news/notice':
		    self.active = 2;
		    break;
		  case '/user/center':
		    self.active = 3;
		    break;
		}
	}
}
</script>
<style lang="less">
  .van-tabbar--fixed{
    border-top: 1px solid #EEEEEE;
  }
</style>
