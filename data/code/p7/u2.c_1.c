#
include < stdio.h >
    int main() {
        int n, a[10000];
        scanf("%d", & n);
        for (int i = 0; i < n; i++) {
            scanf("%d", & a[i]);
        }
        for (int i = 0; i < n; i++) {
            for (int j = n - 1; j > i; j--) {
                if (a[j] < a[j - 1]) {
                    int tam = a[j - 1];
                    a[j - 1] = a[j];
                    a[j] = tam;
                }
            }
        }
        for (int i = 0; i < n; i++) {
            printf("%d ", a[i]);
        }
    }