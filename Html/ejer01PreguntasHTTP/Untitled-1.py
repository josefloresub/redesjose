def es_primo(numero):
    if numero < 2:
        return False
    for i in range(2, int(numero ** 0.5) + 1):
        if numero % i == 0:
            return False
    return True

def primos_hasta(n):
    primos = []
    for i in range(2, n + 1):
        if es_primo(i):
            primos.append(i)
    return primos

# Solicitar un número al usuario
num = int(input("Ingresa un número entero: "))

# Obtener y mostrar los números primos hasta el número ingresado
resultado = primos_hasta(num)
print(f"Los números primos hasta {num} son: {resultado}")
