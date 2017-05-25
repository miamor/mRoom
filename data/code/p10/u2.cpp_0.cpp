#
include < iostream > #include < stdio.h >
    using namespace std;
int usc(int a, int b) {
    while (a != b) {
        if (a > b) a = a - b;
        else b = b - a;
    }
    return (a);
}
int main() {
    int m, n;
    scanf("%d%d", & m, & n);
    printf("%d", usc(m, n));
    return 0;
}